<?php

namespace App\Controller;

use App\Entity\Questions;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Questions::class);
        $questions = $repository->findAll();

        return $this->render('main/main.html.twig', [
            'questions' => $questions,
        ]);
    }
}
