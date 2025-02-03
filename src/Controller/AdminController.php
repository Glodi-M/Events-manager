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
        // Vérifier si l'utilisateur est un administrateur
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer tous les utilisateurs
        $users = $userRepository->findAll();

        // Récupérer toutes les inscriptions
        $registrations = $registrationRepository->findAll();

        // Afficher la vue avec les données
        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'registrations' => $registrations,
        ]);
    }


    // La Route pour supprimer un utilisateur
    #[Route('/admin/delete-user/{id}', name: 'admin_delete_user', methods: ['POST'])]
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est un administrateur
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer l'utilisateur à supprimer
        $user = $userRepository->find($id);

        if (!$user) {
            // Rediriger avec un message d'erreur si l'utilisateur n'existe pas
            $this->addFlash('error', 'Utilisateur introuvable.');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        // Rediriger avec un message de succès
        $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }
}
