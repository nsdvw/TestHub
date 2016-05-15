<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\Type\MultipleAnswerForm;
use AppBundle\Form\Type\SingleAnswerForm;
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

    public function questionAction($testId, Request $request)
    {
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

        $attempt = $testService->continueAttemptOrStartNewOne($test, $user);
        $question = $testService->getNextQuestion($attempt);

        if (!$question) {
            return $this->redirectToRoute('result', ['testId' => $testId]);
        }

        $form = $this->resolveForm(
            $question,
            ['attempt_id' => $attempt->getId()]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $answerRepo = $em->getRepository('AppBundle:Answer');
            $answerRepo->populateAndSave($form->getData());
            return $this->redirectToRoute('question', ['testId' => $testId]);
        }

        return $this->render('default/question.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
            'attempt' => $attempt,
        ], $response);
    }

    public function resultAction($testId)
    {
        return new Response("Test completed");
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
