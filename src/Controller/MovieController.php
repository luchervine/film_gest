<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('/', name: 'app_movie_index', methods: ['GET'])]
    public function index(MovieRepository $movieRepository, RequestStack $requestStack): Response
    {
        //get session variable
        $session = $requestStack->getSession();
        $user = $session->get('userSession', null);
        
        if($user){
            return $this->render('movie/index.html.twig', [
                'movies' => $movieRepository->findAll(),
            ]);
        }
        else return $this->redirectToRoute('app_login');
    }

    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MovieRepository $movieRepository, SluggerInterface $slugger, RequestStack $requestStack): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        $session = $requestStack->getSession();
        $user = $session->get('userSession', null);
        
        if($user){
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form['image']->getData();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'_'.uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('pictures'), $newFilename);
                $movie->setImage($newFilename);
                
                $movieRepository->add($movie, true);
    
                return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->renderForm('movie/new.html.twig', [
                'movie' => $movie,
                'form' => $form,
            ]);
        }
        else return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}', name: 'app_movie_show', methods: ['GET'])]
    public function show(Movie $movie, RequestStack $requestStack): Response
    {
        //get session variable
        $session = $requestStack->getSession();
        $user = $session->get('userSession', null);
        
        if($user){
            return $this->render('movie/show.html.twig', [
                'movie' => $movie,
            ]);
        }
        else return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Movie $movie, MovieRepository $movieRepository, SluggerInterface $slugger, RequestStack $requestStack): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        //get session variable
        $session = $requestStack->getSession();
        $user = $session->get('userSession', null);
        
        if($user){
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form['image']->getData();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'_'.uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('pictures'), $newFilename);
                $movie->setImage($newFilename);
                $movieRepository->add($movie, true);
    
                return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->renderForm('movie/edit.html.twig', [
                'movie' => $movie,
                'form' => $form,
            ]);
        }
        else return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}', name: 'app_movie_delete', methods: ['POST'])]
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository, RequestStack $requestStack): Response
    {
        //get session variable
        $session = $requestStack->getSession();
        $user = $session->get('userSession', null);
        
        if($user){
            if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
                $movieRepository->remove($movie, true);
            }
    
            return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
        }
        else return $this->redirectToRoute('app_login');
    }
}
