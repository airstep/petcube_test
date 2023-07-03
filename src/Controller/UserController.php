<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    /** @var User */
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {

        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/me", name="me")
     */
    public function findMe(): Response
    {
        return $this->render('user/index.html.twig', [
            'id'    => $this->user->getId(),
            'name'  => $this->user->getName(),
            'email' => $this->user->getEmail(),
        ]);
    }
}
