<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends AbstractController
{
    
    #[Route('/hello/world', name: 'hello_world')]    
    public function index(): Response
    {   
        $titre = 'Hello world';
        $text = 'je suis un nouveau paragraphe';

        $list = [
            1,
            2,
            3,
            4,
            5
        ];
        
       return $this->render('hello_world/index.html.twig', [
            'titre' => $titre,
            'vars' => $text,
            'list'=>$list
        ]);
        
    }
}
