<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RegistrationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminController extends AbstractController
{

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(UserRepository $userRepository, RegistrationRepository $registrationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $userRepository->findAll();
        $registrations = $registrationRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'registrations' => $registrations,
        ]);
    }


    // La Route pour supprimer un utilisateur

    #[Route('/admin/delete-user/{id}', name: 'admin_delete_user', methods: ['POST'])]
    public function deleteUser(int $id, UserRepository $userRepository, RegistrationRepository $registrationRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $userRepository->find($id);

        if (!$user) {
            $this->addFlash('error', 'Utilisateur introuvable.');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Vérifier si l'utilisateur a des inscriptions

        $registrations = $registrationRepository->findBy(['user' => $user]);

        if (count($registrations) > 0) {
            $this->addFlash('error', 'Impossible de supprimer cet utilisateur car il est inscrit à un événement.');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }


    // La Route pour supprimer l'inscription à un événement de l'utilisateur

    #[Route('/admin/delete-registration/{id}', name: 'admin_delete_registration', methods: ['POST'])]
    public function deleteRegistration(int $id, RegistrationRepository $registrationRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer l'inscription à supprimer
        $registration = $registrationRepository->find($id);

        if (!$registration) {
            // Rediriger avec un message d'erreur si l'inscription n'existe pas
            $this->addFlash('error', 'Inscription introuvable.');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Supprimer l'inscription
        $entityManager->remove($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Inscription supprimée avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }
}
