<?php

namespace App\Controller;

use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/api", name="api_")
 */
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login', methods: ['POST'])]
    /*public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'//.$error
            ], 400);
        }

        return $this->json([
            'user' => $this->getUser() ? $this->getUser()->getId() : null,
        ]);

        return $this->json('Error');
    }*/

    public function login(TokenStorageInterface $tokenStorage, Request $request)
    {
        $user = $this->getUser();
        if( $user ){

            // Create a response object so we can attach the Remember Me cookie
            // to be sent back to the client
            $response = new JsonResponse( [
                'username' => $user->getEmail(),
                'roles' => $user->getRoles()
            ] );


            // Capture the JSON Payload posted from the client
            $payload = [];
            if( $request->getContentType() === 'json' ) {
              $payload = json_decode($request->getContent(), true);

              if (json_last_error() !== JSON_ERROR_NONE) {
                  throw new \Exception('Invalid json: ' . json_last_error_msg() );
              }
            }


            return $response;
        }

        return new JsonResponse( [
            'message' => 'Login failed'
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
