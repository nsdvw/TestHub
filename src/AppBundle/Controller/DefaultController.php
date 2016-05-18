<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\Type\TextAnswerForm;
use AppBundle\Form\Type\VariantAnswerForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testRepo = $em->getRepository("AppBundle:Test");
        $tests = $testRepo->findNewest(5);

        return $this->render('default/index.html.twig', ['tests' => $tests]);
    }

    public function prefaceAction($id)
    {

    }

    public function questionAction($testId, $num, Request $request)
    {
        $num = intval($num);
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('user_manager');
        $testService = $this->get('test_service');

        $test = $em->find('AppBundle:Test', $testId);
        if (!$test) {
            throw $this->createNotFoundException('Теста под таким номером не существует');
        }

        $response = new Response();
        if (!$user = $um->getUser($request)) {
            $user = $um->createGuestUser();
            $um->loginGuestUser($response, $user);
        }

        $attempt = $testService->findLastAttempt($test, $user);

        if (!$attempt) {
            $attempt = $testService->createNewAttempt($test, $user);
        } elseif ($attempt->getStatus() === 'completed') {
            $attempt = $testService->createNewAttempt($test, $user);
        } else {
            if ($attempt->getTimeLeft() === 0) {
                return $this->redirectToRoute('result', ['testId' => $test->getId()]);
            }
        }

        if ($num != 0) {
            $question = $testService->findByNum($num, $test->getId());
            if (!$question) {
                return $this->redirectToRoute('question', ['testId'=>$test->getId()]);
            }
            if ($testService->questionAlreadyHasAnswer($attempt, $question)) {
                return $this->redirectToRoute('question', ['testId'=>$test->getId()]);
            }
        } else {
            $question = $testService->getFirstUnanswered($attempt);
            if (!$question) {
                return $this->redirectToRoute('question', ['testId'=>$test->getId()]);
            }
        }

        $form = $this->resolveForm(
            $question,
            ['attempt_id' => $attempt->getId()]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $answerRepo = $em->getRepository('AppBundle:Answer');
            $answerRepo->createAndSave($form->getData());
            if ($num !== 0) {
                $nextNum = $testService->getNextUnansweredNumber($attempt, $num);
            } else {
                $nextNum = $testService->getNextUnansweredNumber(
                    $attempt,
                    $question->getSequenceNumber()
                );
            }
            if (!$nextNum) {
                if ($testService->getUnansweredCount($attempt) === 0) {
                    return $this->redirectToRoute('result', ['testId' => $test->getId()]);
                }
                return $this->redirectToRoute('confirm', ['attemptID' => $attempt->getId()]);
            }
            return $this->redirectToRoute(
                'question',
                ['testId' => $testId, 'num' => $nextNum]
            );
        }

        return $this->render('default/question.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
            'attempt' => $attempt,
        ], $response);
    }

    public function resultAction($attemptID)
    {
        return new Response(
            "Тест пройден. В дальнейшем здесь можно будет посмотреть его статистику."
        );
    }

    public function confirmAction($attemptID, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('user_manager');
        $testService = $this->get('test_service');

        $attempt = $em->find('AppBundle:Attempt', $attemptID);
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

        return $this->render(
            'default/confirm.html.twig',
            ['skippedCount' => $skippedCount, 'attempt' => $attempt]
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
}
