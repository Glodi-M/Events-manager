<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Entity\Registration;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EventsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // La Route pour Afiicher la liste des événements dans la page d'acceuil

    #[Route('/', name: 'home')]
    public function Home(EventRepository $repo)
    {
        // Utilisation de l'EntityManager pour accéder au repository
        // $repo = $this->entityManager->getRepository(Event::class);

        $events = $repo->findAll();

        return $this->render('events/home.html.twig', [
            'controller_name' => 'EventsController',
            'events' => $events
        ]);
    }

    // La Route pour ajouter, Modifier et Supprimer l'événement Administrateur


    #[Route(path: '/admin/event/{id}/delete', name: 'event_delete')]
    public function delete(Event $event)
    {
        $this->entityManager->remove($event);
        $this->entityManager->flush();

        $this->addFlash('success', 'Événement supprimé avec succès !');
        return $this->redirectToRoute('home');
    }


    #[Route(path: '/admin/event/creat', name: 'create_event')]
    #[Route(path: '/admin/event/{id}/edit', name: 'event_edit')]

    public function form(Event $event = null, Request $request)
    {
        if (!$event) {

            // Création d'un nouvel événement

            $event = new Event();
        }

        // j'appelle mon formulaire crée en ligne de commande venant du dossier Form

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        // Enregistrement de données 

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($event);
            $this->entityManager->flush();

            // Message flash dynamique selon si c'est une création ou une modification

            $message = $event->getId() ? 'Événement modifié avec succès !' : 'Événement créé avec succès !';
            $this->addFlash('success', $message);



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

        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        $isRegistered = false;

        if ($user) {
            // Vérifier si l'utilisateur est inscrit à cet événement
            $registrationRepo = $this->entityManager->getRepository(Registration::class);
            $registration = $registrationRepo->findOneBy([
                'event' => $event,
                'user' => $user,
            ]);

            // Si une inscription est trouvée, l'utilisateur est déjà inscrit
            $isRegistered = $registration !== null;
        }

        return $this->render('events/show.html.twig', [
            'event' => $event,
            'isRegistered' => $isRegistered,
            'controller_name' => 'EventsController'
        ]);
    }
}
