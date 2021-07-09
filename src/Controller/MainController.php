<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        //dd($this->getUser());
        if($this->getUser()){
            return $this->redirectToRoute('outing_list' );
        }else{
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    }

    #[Route('/cities', name: 'main_cities')]
    public function cities(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('cities/list.html.twig');
    }

    #[Route('/campus', name: 'main_campus')]
    public function campus(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('campus/list.html.twig');
    }
}
