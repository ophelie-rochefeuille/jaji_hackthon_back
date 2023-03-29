<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api", name="api_")
 */

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine
            ->getRepository(User::class)
            ->findAll();

        $data = [];

        foreach ($users as $user) {
            $localPackage = new UrlPackage(
                ['http://localhost:8000/pictures/profile', 'http://127.0.0.1:8000/pictures/profile'],
                new EmptyVersionStrategy()
            );
            $link = '';
            if($user->getPhotoProfil()) $link = $localPackage->getUrl($user->getPhotoProfil());

            $data[] = [
                'id' => $user->getId(),
                'firstName' => $user->getFirstname(),
                'lastName' => $user->getLastname(),
                'profile_picture' => $link
            ];
        }


        return $this->json($data);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $entityManager = $doctrine->getManager();

        $user->setFirstname($request->request->get('firstname'));
        $user->setLastname($request->request->get('lastname'));
        $user->setEmail($request->request->get('email'));

        /*$hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );*/
        $user->setPassword($request->request->get('password'));
        $photofile = $request->request->get('profile_photo');
        //$user->setPhotoProfil();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCookie($request->request->get('cookie'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json('Created new user successfully with id ' . $user->getId());
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
