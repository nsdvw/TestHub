<?php
namespace TestHubBundle\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use TestHubBundle\Entity\Attempt;
use TestHubBundle\Entity\Question;
use TestHubBundle\Form\Type\TextAnswerForm;
use TestHubBundle\Form\Type\VariantAnswerForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testRepo = $em->getRepository("TestHubBundle:Test");
        $tests = $testRepo->findNewest(5);

        return $this->render('test/index.html.twig', ['tests' => $tests]);
    }

    public function startAction($testID, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $test = $em->find('TestHubBundle:Test', $testID);
        if (!$test) {
            throw $this->createNotFoundException();
        }

        $user = $this->get('user_manager')->getUser($request);
        if (!$user) {
            throw new \Exception('Необходима поддержка кукис!');
        }

        if ($this->get('test_service')->hasActiveAttempt($user, $test)) {
            return $this->redirectToRoute('preface', ['testID' => $test->getId()]);
        }

        $attempt = $this->get('test_service')->createNewAttempt($test, $user);
        $em->persist($attempt);
        $em->flush();

        return $this->redirectToRoute(
            'question',
            ['attemptID' => $attempt->getId(), 'questionNumber' => 1]
        );
    }

    public function prefaceAction($testID, Request $request)
    {
        $response = new Response();
        $em = $this->getDoctrine()->getManager();
        $test = $em->find('TestHubBundle:Test', $testID);
        if (!$test) {
            throw $this->createNotFoundException();
        }

        $user = $this->get('user_manager')->getUser($request);
        if ($user) {
            if ($this->get('test_service')->hasActiveAttempt($user, $test)) {
                $attempt = $this->get('test_service')->findLastActiveAttempt($user, $test);
                $unanswered = $this->get('test_service')->getUnansweredCount($attempt);
                return $this->render(
                    'test/continue.html.twig',
                    ['attempt' => $attempt, 'unanswered' => $unanswered]
                );
            }
        } else {
            $this->createGuestAndLogin($response);
        }

        $questionsCount = $this->get('test_service')->getQuestionsCount($test);
        $maxMark = $this->get('calculator')->calculateMaxMark($test);

        return $this->render('test/preface.html.twig',
            [
                'test' => $test,
                'questionsCount' => $questionsCount,
                'maxMark' => $maxMark,
            ],
            $response
        );
    }

    public function questionAction($attemptID, $questionNumber, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $attempt = $em->find('TestHubBundle:Attempt', $attemptID);

        if (!$attempt) {
            throw $this->createNotFoundException();
        }

        if ($attempt->isCompleted()) {
            return $this->redirectToRoute('result', ['attemptID' => $attemptID]);
        }

        $repo = $em->getRepository('TestHubBundle:Question');
        $question = $repo->findOneBy([
            'test' => $attempt->getTest(),
            'sequenceNumber' => $questionNumber,
        ]);

        if (!$question) {
            throw $this->createNotFoundException();
        }

        $user = $this->get('user_manager')->getUser($request);
        if (!$user) {
            throw new AccessDeniedHttpException('Необходима поддержка кукис');
        }
        if (!$this->get('user_manager')->hasRights($user, $attempt)) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->resolveForm($question, ['attempt_id' => $attemptID]);
        $repo = $em->getRepository('TestHubBundle:Answer');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $answers = $repo->create($form->getData());
            foreach ($answers as $answer) {
                $repo->save($answer);
            }

            $service = $this->get('test_service');
            $nextNum = $service->getNextUnansweredNumber($attempt, $questionNumber);

            if (!$nextNum) {
                $firstSkipped = $this->get('test_service')->getFirstUnansweredNumber($attempt);
                if ($firstSkipped) {
                    return $this->redirectToRoute('confirm', ['attemptID' => $attemptID]);
                }
                return $this->redirectToRoute('result', ['attemptID' => $attemptID]);
            }

            return $this->redirectToRoute('question', [
                'attemptID' => $attemptID,
                'questionNumber' => $nextNum,
            ]);
        }

        return $this->render('test/question.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
            'attempt' => $attempt,
        ]);
    }

    public function resultAction($attemptID, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $attempt = $em->find('TestHubBundle:Attempt', $attemptID);

        if (!$attempt) {
            throw $this->createNotFoundException();
        }

        $user = $this->get('user_manager')->getUser($request);
        if ($user == $attempt->getTrier()) {
            $attempt->setStatus(Attempt::COMPLETED);
            $em->persist($attempt);
        }

        $mark = $this->get('calculator')->calculateMark($attempt);
        $maxMark = $this->get('calculator')->calculateMaxMark($attempt->getTest());
        $questionsCount =
            $this->get('testService')->getQuestionsCount($attempt->getTest());
        $correctAnswers = $this->get('calculator')->countCorrectAnswers($attempt);

        $em->flush();

        return $this->render(
            'test/result.html.twig',
            [
                'mark' => $mark,
                'maxMark' => $maxMark,
                'qCount' => $questionsCount,
                'correct' => $correctAnswers
            ]
        );
    }

    public function confirmAction($attemptID, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('user_manager');
        $testService = $this->get('test_service');

        $attempt = $em->find('TestHubBundle:Attempt', $attemptID);
        if (!$attempt) {
            throw $this->createNotFoundException('Информация не найдена');
        }
        if (!$user = $um->getUser($request)) {
            throw $this->createNotFoundException('У вас нет прав на это действие');
        }
        if ($attempt->getTrier()->getId() != $user->getId()) {
            throw $this->createNotFoundException('У вас нет прав на это действие');
        }

        $skippedCount = $testService->getUnansweredCount($attempt);
        $firstSkipped = $testService->getFirstUnansweredNumber($attempt);

        return $this->render(
            'test/confirm.html.twig',
            [
                'skippedCount' => $skippedCount,
                'attempt' => $attempt,
                'firstSkipped' => $firstSkipped
            ]
        );
    }

    private function resolveForm(Question $question, array $data)
    {
        $data['question_id'] = $question->getId();
        $data['choices'] = $question->getChoices();

        switch ($question->getType()) {
            case Question::TEXT:
            case Question::DECIMAL:
                return $this->createForm(TextAnswerForm::class, $data);
            case Question::SINGLE:
                $data['expanded'] = true;
                $data['multiple'] = false;
                return $this->createForm(VariantAnswerForm::class, $data);
            case Question::MULTIPLE:
                $data['expanded'] = true;
                $data['multiple'] = true;
                return $this->createForm(VariantAnswerForm::class, $data);
            default:
                return null;
        }
    }

    private function createGuestAndLogin(Response $response)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('user_manager')->createGuestUser();
        $uid = $this->get('user_manager')->save($user);
        $user = $em->find('TestHubBundle:User', $uid);
        $this->get('user_manager')->loginGuestUser($response, $user);
    }
}
