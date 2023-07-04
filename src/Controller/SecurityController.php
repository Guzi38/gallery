<?php
/**
 * Security controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
    /**
     * Handles the login functionality and displays the login form.
     *
     * @param AuthenticationUtils $authenticationUtils the AuthenticationUtils service for retrieving authentication related data
     *
     * @return Response the response containing the rendered login form
     *
     * @Route(path="/login", name="app_login")
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Handles the logout functionality.
     *
     * @throws \LogicException this method can be blank - it will be intercepted by the logout key on your firewall
     *
     * @Route(path="/logout", name="app_logout")
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
