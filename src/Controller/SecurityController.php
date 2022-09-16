<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Exception;
class SecurityController extends AbstractController
{
    /**
     * @Route("/authentication", name="security_login")
     * @return Response
     */
    public function login(): Response
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }
}
