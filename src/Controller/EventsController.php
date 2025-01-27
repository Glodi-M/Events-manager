<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface; // Ajout de cette ligne pour utiliser l'EntityManager
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventsController extends AbstractController
{
    private $entityManager;

    // Injection de l'EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    // La route pour Connexion

    #[Route('/events', name: 'app_events')]
    public function index(): Response
    {
        return $this->render('events/index.html.twig', [
            'controller_name' => 'EventsController',
        ]);
    }


    // La Route pour Afiicher la liste des événements dans la page d'acceuil

    #[Route('/', name: 'home')]
    public function Home(EventRepository $repo)
    {
        // Utilisation de l'EntityManager pour accéder au repository
        // $repo = $this->entityManager->getRepository(Event::class);

        // Exemple de récupération des événements
        $events = $repo->findAll();

        return $this->render('events/home.html.twig', [
            'controller_name' => 'EventsController',
            'events' => $events,  // Passer les événements à la vue
        ]);
    }

    // route pour voir plus

    #[Route('/events/event/{id}', name: 'events_show')]
    public function Show($id)

    {
        // Récupérer l'événement spécifique par son ID
        $repo = $this->entityManager->getRepository(Event::class);
        $event = $repo->find($id);

        // Si l'événement n'existe pas, rediriger vers la page d'accueil
        if (!$event) {
            return $this->redirectToRoute('home');
        }

        return $this->render('events/show.html.twig', [
            'event' => $event,
            'controller_name' => 'EventsController',
        ]);
    }
}
