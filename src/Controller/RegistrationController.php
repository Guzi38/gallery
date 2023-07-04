<?php
/**
 * Registration controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * User service.
     */
    private UserService $userService;

    /**
     * RegistrationController constructor.
     *
     * @param UserService $userService User service
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register.
     *
     * @param Request                     $request            HTTP request
     * @param UserPasswordHasherInterface $userPasswordHasher User password hasher
     * @param UserAuthenticatorInterface  $userAuthenticator  User authenticator
     * @param LoginFormAuthenticator      $authenticator      Login form authenticator
     *
     * @return Response HTTP response
     */
    #[Route(
        '/register',
        name: 'app_register',
        methods: ['GET', 'POST']
    )]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->userService->save($user);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
