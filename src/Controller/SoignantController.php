<?php

namespace App\Controller;

use App\Entity\Soignant;
use App\Form\SoignantType;
use App\Repository\SoignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/soignant')]
class SoignantController extends AbstractController
{
    #[Route('/', name: 'app_soignant_index', methods: ['GET'])]
    public function index(SoignantRepository $soignantRepository): Response
    {
        return $this->render('soignant/index.html.twig', [
            'soignants' => $soignantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_soignant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SoignantRepository $soignantRepository): Response
    {
        $soignant = new Soignant();
        $form = $this->createForm(SoignantType::class, $soignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soignantRepository->save($soignant, true);

            return $this->redirectToRoute('app_soignant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('soignant/new.html.twig', [
            'soignant' => $soignant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_soignant_show', methods: ['GET'])]
    public function show(Soignant $soignant): Response
    {
        return $this->render('soignant/show.html.twig', [
            'soignant' => $soignant,
        ]);
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
