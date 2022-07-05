<?php

namespace App\Controller;

use App\Repository\ActorRepository;
use App\Repository\DirectorRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home', methods:['GET'])]
    public function index(DirectorRepository $directorRepository,
    ActorRepository $actorRepository, MovieRepository $movieRepository, RequestStack $requestStack
    ): ?Response
    {
        //get session variable
        $session = $requestStack->getSession();
        $user = $session->get('userSession', null);
        
        if($user){
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'directors' => $directorRepository->findAll(),
                'actors' => $actorRepository->findAll(),
                'movies' => $movieRepository->findAll(),
            ]);
        }
        else return $this->redirectToRoute('app_login');
    }
}
