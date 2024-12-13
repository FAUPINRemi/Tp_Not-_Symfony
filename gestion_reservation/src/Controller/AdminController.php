<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Form\UserType;
use App\Form\ReservationType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(UserRepository $userRepository, ReservationRepository $reservationRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
            'reservations' => $reservationRepository->findAll()
        ]);
    }
/**
     * @OA\Get(
     *     path="/admin/users",
     *     summary="Liste des utilisateurs",
     *     @OA\Response(
     *         response=200,
     *         description="Retourne la liste des utilisateurs"
     *     )
     * )
     */
    #[Route('/users', name: 'admin_users_list')]
    public function listUsers(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/user/new', name: 'admin_user_new')]
    public function newUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur créé avec succès');
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/users/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/edit/{id}', name: 'admin_user_edit')]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/user/delete/{id}', name: 'admin_user_delete')]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('admin_users_list');
    }

    #[Route('/reservations', name: 'admin_reservations_list')]
    public function listReservations(ReservationRepository $reservationRepository): Response
    {
        return $this->render('admin/reservations/index.html.twig', [
            'reservations' => $reservationRepository->findAll()
        ]);
    }

    #[Route('/reservation/new', name: 'admin_reservation_new')]
    public function newReservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation créée avec succès');
            return $this->redirectToRoute('admin_reservations_list');
        }

        return $this->render('admin/reservations/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/reservation/edit/{id}', name: 'admin_reservation_edit')]
    public function editReservation(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Réservation modifiée avec succès');
            return $this->redirectToRoute('admin_reservations_list');
        }

        return $this->render('admin/reservations/edit.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation
        ]);
    }

    #[Route('/reservation/delete/{id}', name: 'admin_reservation_delete')]
    public function deleteReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($reservation);
        $entityManager->flush();
        $this->addFlash('success', 'Réservation supprimée avec succès');
        return $this->redirectToRoute('admin_reservations_list');
    }

    #[Route('/user/new-admin', name: 'app_admin_new')]
public function newAdmin(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
{
    // Create new user with ROLE_ADMIN
    $user = new User();
    $user->setRoles(['ROLE_ADMIN']);

    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Encode password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

        // Save to database
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Admin account created successfully');
        return $this->redirectToRoute('admin_dashboard');
    }

    return $this->render('admin/new_admin.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}
}