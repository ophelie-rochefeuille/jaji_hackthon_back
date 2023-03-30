<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Parcours;
use App\Entity\Soignant;
use App\Form\FormationType;
use App\Repository\FormationRepository;
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


#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $formations = $doctrine
            ->getRepository(Formation::class)
            ->findAll();

        $data = [];

        foreach ($formations as $formation) {
            $localPackage = new UrlPackage(
                ['http://localhost:8000/pictures/formation', 'http://127.0.0.1:8000/pictures/formation'],
                new EmptyVersionStrategy()
            );
            $link = '';
            if($formation->getImage()) $link = $localPackage->getUrl($formation->getImage());
            $data[] = [
                'id' => $formation->getId(),
                'title' => $formation->getTitle(),
                'description' => $formation->getDescription(),
                'soignant' => $formation->getSoignant(),
                'url' => $formation->getUrl(),
                'image' => $link
            ];
        }


        return $this->json($data);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormationRepository $formationRepository, ManagerRegistry $doctrine): Response
    {
        $formation = new Formation();
        $entityManager = $doctrine->getManager();

        $formation->setTitle($request->request->get('title'));
        $formation->setDescription($request->request->get('description'));
        $formation->setUrl($request->request->get('url'));
        $parcours_id = $request->request->get('parcours');
        $formation->setParcours($doctrine->getRepository(Parcours::class)->findOneBy(['id'=>$parcours_id]));
        $soignant_id = $request->request->get('soignant');
        $formation->addSoignant($doctrine->getRepository(Soignant::class)->findOneBy(['id'=>$soignant_id]));

        $entityManager->persist($formation);
        $entityManager->flush();

        return $this->json('Created new formation successfully with id ' . $formation->getId());

    }

    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        $data = [];

        $localPackage = new UrlPackage(
            ['http://localhost:8000/pictures/formation', 'http://127.0.0.1:8000/pictures/formation'],
            new EmptyVersionStrategy()
        );
        $link = '';
        if($formation->getImage()) $link = $localPackage->getUrl($formation->getImage());

        $data[] = [
            'id' => $formation->getId(),
            'title' => $formation->getTitle(),
            'description' => $formation->getDescription(),
            'soignant' => $formation->getSoignant(),
            'url' => $formation->getUrl(),
            'parcours_id' => $formation->getParcours()->getId(),
            'image' => $link
        ];

        return $this->json($data);
    }

    #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formationRepository->save($formation, true);

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $formationRepository->remove($formation, true);
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
