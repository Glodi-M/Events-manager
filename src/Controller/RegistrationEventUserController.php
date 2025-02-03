<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Registration;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RegistrationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RegistrationEventUserController extends AbstractController
{

    // La Route pour S'inscrire dans un éneveement

    #[Route('/user/event/{id}/registerEvent', name: 'event_register')]
    public function register(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer l'événement
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            $this->addFlash('error', 'Événement introuvable.');
            return $this->redirectToRoute('home');
        }

        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour vous inscrire.');
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si l'utilisateur est déjà inscrit
        foreach ($event->getRegistrations() as $registration) {
            if ($registration->getUser() === $user) {
                $this->addFlash('warning', 'Vous êtes déjà inscrit à cet événement.');
                return $this->redirectToRoute('events_show', ['id' => $id]);
            }
        }

        // Créer une nouvelle inscription
        $registration = new Registration();
        $registration->setUser($user);
        $registration->setEvent($event);
        $registration->setDate(new \DateTime());

        // Sauvegarde en base
        $entityManager->persist($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Inscription réussie !');
        return $this->redirectToRoute('events_show', ['id' => $id]);
    }


    // La Route pour Afficher les événements inscrits

    #[Route('/user/mes-evenements', name: 'my_events')]

    public function myEvents(RegistrationRepository $registrationRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les inscriptions de l'utilisateur
        $registrations = $registrationRepository->findByUser($user);

        // Récupérer les événements à partir des inscriptions
        $events = [];
        foreach ($registrations as $registration) {
            $events[] = $registration->getEvent();
        }

        // Afficher la vue avec les événements
        return $this->render('registration_event_user/my_events.html.twig', [
            'events' => $events,
        ]);
    }


    // La Route pour se Desinscrire à un événement

    #[Route('/user/desinscrire/{eventId}', name: 'event_unregister')]
    public function unregister(
        int $eventId,
        EntityManagerInterface $entityManager,
        RegistrationRepository $registrationRepository
    ): Response {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            $this->addFlash('error', 'Vous devez être connecté pour vous désinscrire.');
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'inscription de l'utilisateur pour cet événement
        $registration = $registrationRepository->findOneBy([
            'user' => $user,
            'event' => $eventId,
        ]);

        if (!$registration) {
            // Rediriger avec un message d'erreur si l'inscription n'existe pas
            $this->addFlash('error', 'Vous n\'êtes pas inscrit à cet événement.');
            return $this->redirectToRoute('my_events');
        }

        // Supprimer l'inscription
        $entityManager->remove($registration);
        $entityManager->flush();

        // Rediriger avec un message de succès
        $this->addFlash('success', 'Vous êtes désormais désinscrit de cet événement.');
        return $this->redirectToRoute('my_events');
    }
}
