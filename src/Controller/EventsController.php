<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventsController extends AbstractController{
    #[Route('/events', name: 'app_events')]
    public function index(): Response
    {
        return $this->render('events/index.html.twig', [
            'controller_name' => 'EventsController',
        ]);
    }

    // Creation de la Route de la page d'accueil du site  


    #[Route ('/', name:'home' )]
    public function Home() {

        return $this->render('events/home.html.twig', parameters: [
            'controller_name' => 'EventsController',
        ]);
    }
}
