<?php

namespace App\Controller;

use App\Entity\Director;
use App\Form\DirectorType;
use App\Repository\DirectorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/director')]
class DirectorController extends AbstractController
{
    #[Route('/', name: 'app_director_index', methods: ['GET'])]
    public function index(DirectorRepository $directorRepository): Response
    {
        return $this->render('director/index.html.twig', [
            'directors' => $directorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_director_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DirectorRepository $directorRepository): Response
    {
        $director = new Director();
        $form = $this->createForm(DirectorType::class, $director);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $directorRepository->add($director, true);

            return $this->redirectToRoute('app_director_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('director/new.html.twig', [
            'director' => $director,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_director_show', methods: ['GET'])]
    public function show(Director $director): Response
    {
        return $this->render('director/show.html.twig', [
            'director' => $director,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_director_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Director $director, DirectorRepository $directorRepository): Response
    {
        $form = $this->createForm(DirectorType::class, $director);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $directorRepository->add($director, true);

            return $this->redirectToRoute('app_director_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('director/edit.html.twig', [
            'director' => $director,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_director_delete', methods: ['POST'])]
    public function delete(Request $request, Director $director, DirectorRepository $directorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$director->getId(), $request->request->get('_token'))) {
            $directorRepository->remove($director, true);
        }

        return $this->redirectToRoute('app_director_index', [], Response::HTTP_SEE_OTHER);
    }
}
