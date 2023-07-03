<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/password", name="change_password")
     */
    public function changePassword(Request $request, TokenStorageInterface $tokenStorage)
    {
        // Get entity manager
        $entityManager = $this->getDoctrine()->getManager();
        // Get current user
        /** @var User */
        $user = $tokenStorage->getToken()->getUser();
        // Create change password form
        $form = $this->createForm(ChangePasswordType::class, $user);
        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode new password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            // Save changes to database
            $entityManager->flush();
            // Redirect to login page
            return $this->redirectToRoute('app_login');
        }
        // Render change password form
        return $this->render('password/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
