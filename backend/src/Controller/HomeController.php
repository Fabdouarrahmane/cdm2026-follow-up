<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/phase/{id}', name: 'app_phase')]
    public function phase(int $id): Response
    {
        return $this->render('home/phase.html.twig', [
            'phaseId' => $id
        ]);
    }

    #[Route('/match/{id}', name: 'app_match')]
    public function match(int $id): Response
    {
        return $this->render('home/match.html.twig', [
            'matchId' => $id
        ]);
    }
}
