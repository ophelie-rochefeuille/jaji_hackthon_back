<?php

namespace App\Controller;
use App\Entity\Formation;
use App\Entity\Parcours;
use App\Entity\Soignant;
use App\Entity\User;
use App\Form\ParcoursType;
use App\Repository\ParcoursRepository;
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

#[Route('/parcours')]
class ParcoursController extends AbstractController
{
    #[Route('/', name: 'app_parcours_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $parcours_list = $doctrine
            ->getRepository(Parcours::class)
            ->findAll();

        $data = [];

        foreach ($parcours_list as $parcours) {

            $localPackage = new UrlPackage(
                ['http://localhost:8000/pictures/soignant', 'http://127.0.0.1:8000/pictures/soignant'],
                new EmptyVersionStrategy()
            );
            $link = '';
            if($parcours->getImage()) $link = $localPackage->getUrl($parcours->getImage());

            $data[] = [
                'id' => $parcours->getId(),
                'title' => $parcours->getTitle(),
                'description' => $parcours->getDescription(),
                'image' => $link,
                'chronologie' => $parcours->getChronologie()
            ];
        }


        return $this->json($data);
    }

    #[Route('/new', name: 'app_parcours_new', methods: [ 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $parcours = new Parcours();
        $parcours->setTitle($request->request->get('title'));
        $parcours->setDescription($request->request->get('description'));
        $user_id = $request->request->get('user_id');
        $user = $doctrine->getRepository(User::class)->findOneBy(['id'=>$user_id]);
        if($user) $parcours->setUserId();
        $formation_id = $request->request->get('formation');
        $formation = $doctrine->getRepository(Formation::class)->findOneBy(['id'=>$formation_id]);
        if($formation) $parcours->addFormation($formation);
        $imagefile = $request->request->get('image');
        //$parcours->setImage();
        if($request->request->get('question1')) $parcours->setTitleQuizz1($request->request->get('question1'));
        if($request->request->get('rep1')) $parcours->setTitleQuizz1($request->request->get('rep1'));
        if($request->request->get('question2')) $parcours->setTitleQuizz1($request->request->get('question2'));
        if($request->request->get('rep2')) $parcours->setTitleQuizz1($request->request->get('rep2'));

        if($request->request->get('video_url')) $parcours->setVideoUrl($request->request->get('video_url'));

        $entityManager->persist($parcours);
        $entityManager->flush();

        return $this->json('Created new soignant successfully with id ' . $parcours->getId());
    }

    #[Route('/{id}', name: 'app_parcours_show', methods: ['GET'])]
    public function show(Parcours $parcour): Response
    {
        return $this->render('parcours/show.html.twig', [
            'parcour' => $parcour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parcours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parcours $parcour, ParcoursRepository $parcoursRepository): Response
    {
        $form = $this->createForm(ParcoursType::class, $parcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parcoursRepository->save($parcour, true);

            return $this->redirectToRoute('app_parcours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parcours/edit.html.twig', [
            'parcour' => $parcour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parcours_delete', methods: ['POST'])]
    public function delete(Request $request, Parcours $parcour, ParcoursRepository $parcoursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parcour->getId(), $request->request->get('_token'))) {
            $parcoursRepository->remove($parcour, true);
        }

        return $this->redirectToRoute('app_parcours_index', [], Response::HTTP_SEE_OTHER);
    }
}
