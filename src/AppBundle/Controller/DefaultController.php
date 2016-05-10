<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testRepo = $em->getRepository("AppBundle:Test");
        $tests = $testRepo->findNewest(5);

        return $this->render('default/index.html.twig', [
            'tests' => $tests
        ]);
    }
}
