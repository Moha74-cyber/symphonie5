<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('public/index.html.twig', [
          
        ]);
    }

     #[Route('/make/admin', name: 'make_admin')]
    public function makeAdmin(Request $request, EntityManagerInterface $em): Response
    {
       $user =$this->getUser();

       if (!$user){
           //demander de se connecter
            $this->addFlash('error', 'veuillez vous connecter.');
           //redirect sur home

           return $this->redirectToRoute('app_login');
       }
       

       //ajout du role dans l'entitée $user
       $user->addRole('ROLE_ADMIN');

       //sauvegarde
       $em->flush();

       //redirect sur home
       return $this->redirectToRoute('film_film');
    }   
        
    
}

