<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $userPasswordHasher, 
    Request $request, UserRepository $userRepository, RequestStack $requestStack): Response
    {
        /*$user = $userRepository->findOneBy(array('username'=>$request->request->get('username')));
        $pass = $userPasswordHasher->hashPassword(
            $user,
            $request->request->get('password')
        );
        dd('user', $request->request->get('username'),$request->request->get('password'), $user, $pass);*/
         if ($request->isMethod('POST')) {
            $username=$request->request->get('username');
            $password=md5($request->request->get('password'));
            $user=$userRepository->findOneBy(array('username'=>$username,'password'=>$password));
            
            //create session variable
            $session = $requestStack->getSession();
            $session->set('userSession', $user);

            if($user) return $this->redirectToRoute('app_home');
         }
         
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(RequestStack $requestStack): void
    {
        $session = $requestStack->getSession();
        $session->clear();

        //$this->redirectToRoute('app_login');
    }
}
