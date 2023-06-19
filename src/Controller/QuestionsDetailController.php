<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Form\PublicAnswerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsDetailController extends AbstractController
{
    #[Route('/questions/{id}', name: 'app_questions_page_detailed')]
    public function index($id, ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {
        $repository = $doctrine->getRepository(Questions::class);
        $question = $repository->find($id);

        if(!$question){
            return $this->redirect('/');
        }

        if($question->istoshow() == false){
            return $this->redirect('/');
        }

        $answer = new Answers();
        $user = $this->getUser();
        $formAnswer = $this->createForm(PublicAnswerType::class, $answer);
        $formAnswer->handleRequest($request);

        if ($formAnswer->isSubmitted() && $formAnswer->isValid())
        {
            $date = new \DateTime('@'.strtotime('now + 3 hours'));
            $answer->setDate($date);
            $answer->setUser($user);
            $answer->setQuestion($question);

            $answer->setToshow(0);

            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirect('/');

        }

        return $this->render('questionsDetail/questionsDetail.html.twig', [
            'question' => $question,
            'formAnswer' => $formAnswer->createView(),
        ]);
    }
}
