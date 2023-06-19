<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuestionsFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicQuestionController extends AbstractController
{
    #[Route('/publicQuestion', name: 'app_public_question')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        if ($this->getUser() == null ) {
            return $this->redirectToRoute('app_login');
        }

        $questions = new Questions();
        $formQuestion = $this->createForm(QuestionsFormType::class, $questions);

        $formQuestion->handleRequest($request);
        $user = $this->getUser();

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {

            $question = $formQuestion->getData();
            $date = new \DateTime('@'.strtotime('now + 3 hours'));
            $question->setDate($date);
            $question->setUser($user);


            $manager = $doctrine->getManager();
            $manager->persist($question);
            $manager->flush();
            return $this->redirectToRoute('app_main');
        }


        return $this->render('publicQuestion/publicQuestion.html.twig', [
            'formQuestion' => $formQuestion->createView(),
        ]);
    }
}
