<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface; // Ajout de cette ligne pour utiliser l'EntityManager
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    // La Route pour ajouter l'énévenement

    #[Route(path: '/event/creat', name: 'create_event')]
    #[Route(path: '/event/{id}/edit', name: 'event_edit')]

    public function form(Event $event = null, Request $request)
    {

        if (!$event) {

            // Création du formulaire 

            $event = new event();
        }

        // Création manuellement du formulaire 

        // $form = $this->createFormBuilder($event)
        //     ->add('title')
        //     ->add('content')
        //     ->add('image')
        //     ->add('date')
        //     ->add('place')
        //     ->getForm();


        // j'appelle mon formulaire crée en ligne de commande venant du dossier Form

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        // Enregistrement de données 

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($event);
            $this->entityManager->flush();


            return $this->redirectToRoute('events_show', ['id' => $event->getId()]);
        }

        return $this->render('events/create.html.twig', [
            'formEvent' => $form->createView(),
            'controller_name' => 'EventsController',
            'editMode' => $event->getId() !== null
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
