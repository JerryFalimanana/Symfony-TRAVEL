<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response {
        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte a été crée, vous pouvez maintenant vous connecter");

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'userName' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout() {
        // Rien à mettre car c'est symfony qui va gérer le fait de se déconnecter
    }

    /**
     * Permet d'afficher et de gérer le formulaire de modification de profil
     * 
     * @Route("/account/edit", name = "account_edit")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager): Response {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Les données du profil ont été enregistré avec succès");
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/update-password", name = "account_updatePassword")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response {
        $user = $this->getUser();

        $passwordUpdate = new PasswordUpdate;

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                // errors en utilisant un api symfony
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas le même que votre mot de passe actuel"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Votre mot de passe a été modifié avec succès");

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('account/updatePassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *  Permet d'afficher le profil de l'utilisateur connécté
     * 
     * @Route("/account", name = "account_myAccount")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount(): Response {
        return $this->render('user/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Permet d'afficher la liste des réservations faites par l'utilisateur
     *
     * @Route("/account/bookings", name = "account_bookings")
     * 
     * @return Response
     */
    public function bookings(): Response {
        return $this->render('account/bookings.html.twig');
    }
}
