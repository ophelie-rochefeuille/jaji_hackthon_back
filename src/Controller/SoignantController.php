<?php

namespace App\Controller;
use App\Entity\Soignant;
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

#[Route('/soignant')]
class SoignantController extends AbstractController
{
    #[Route('/', name: 'app_soignant_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $soignants = $doctrine
            ->getRepository(Soignant::class)
            ->findAll();

        $data = [];

        foreach ($soignants as $soignant) {
            $localPackage = new UrlPackage(
                ['http://localhost:8000/pictures/soignant', 'http://127.0.0.1:8000/pictures/soignant'],
                new EmptyVersionStrategy()
            );
            $link = '';
            if($soignant->getImage()) $link = $localPackage->getUrl($soignant->getImage());
            $data[] = [
                'id' => $soignant->getId(),
                'firstName' => $soignant->getFirstname(),
                'lastName' => $soignant->getLastname(),
                'catégorie' => $soignant->getCategory(),
                'doctolib' => $soignant->getDoctolibUrl(),
                'numero' => $soignant->getNumNational(),
                'image' => $link
            ];
        }


        return $this->json($data);
    }

    #[Route('/new', name: 'app_soignant_new', methods: ['POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $soignant = new Soignant();
        $soignant->setFirstname($request->request->get('firstName'));
        $soignant->setLastname($request->request->get('lastName'));
        $soignant->setCategory($request->request->get('catégorie'));
        $soignant->setDoctolibUrl($request->request->get('url'));
        $soignant->setNumNational($request->request->get('numéro'));
        $entityManager->persist($soignant);
        $entityManager->flush();

        return $this->json('Created new soignant successfully with id ' . $soignant->getId());
    }

    #[Route('/{id}', name: 'app_soignant_show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $soignant = $doctrine->getRepository(Soignant::class)->find($id);

        if (!$soignant) {

            return $this->json('No project found for id' . $id, 404);
        }

        $data =  [
            'id' => $soignant->getId(),
            'firstName' => $soignant->getFirstname(),
            'lastName' => $soignant->getLastname(),
            'catégorie' => $soignant->getCategory(),
            'doctolib' => $soignant->getDoctolibUrl(),
            'numero' => $soignant->getNumNational()
        ];

        return $this->json($data);
    }

    #[Route('/{id}/edit', name: 'app_soignant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Soignant $soignant, SoignantRepository $soignantRepository): Response
    {
        $form = $this->createForm(SoignantType::class, $soignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soignantRepository->save($soignant, true);

            return $this->redirectToRoute('app_soignant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('soignant/edit.html.twig', [
            'soignant' => $soignant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_soignant_delete', methods: ['POST'])]
    public function delete(Request $request, Soignant $soignant, SoignantRepository $soignantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$soignant->getId(), $request->request->get('_token'))) {
            $soignantRepository->remove($soignant, true);
        }

        return $this->redirectToRoute('app_soignant_index', [], Response::HTTP_SEE_OTHER);
    }
}
