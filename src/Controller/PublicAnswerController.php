<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicAnswerController extends AbstractController
{
    #[Route('/public/answer', name: 'app_public_answer')]
    public function index(): Response
    {
        return $this->render('publicAnswer/publicAnswer.html.twig', [
            'controller_name' => 'PublicAnswerController',
        ]);
    }
}
