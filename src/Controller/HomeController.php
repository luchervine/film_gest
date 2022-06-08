<?php

namespace App\Controller;

use App\Repository\ActorRepository;
use App\Repository\DirectorRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home', methods:['GET'])]
    public function index(DirectorRepository $directorRepository,
    ActorRepository $actorRepository, MovieRepository $movieRepository
    ): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'directors' => $directorRepository->findAll(),
            'actors' => $actorRepository->findAll(),
            'movies' => $movieRepository->findAll(),
        ]);
    }
}
