<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends AbstractController
{
    #[Route('/hello/world', name: 'hello_world')]
    public function index(): Response
    {
        //return new Response('');

        $gershtdfyuik = 'Hellow world';
        $texte = 'Bonjour je suis du texte';

        $list = [
            'un',
            'deux',
            'trois',
            'quatre',
        ];

        return $this->render('hello_world/index.html.twig', [
            'titre' => $gershtdfyuik,
            'text'  => $texte,
            'list'  => $list,
        ]);
    }

    #[Route('/person', name: 'app_person')]
    public function person(): Response
    {
        $person = new Person();
        $person->id = 1;
        $person->firstname = 'kévin';
        $person->name = 'michu';
        $person->sanitaryPass = false;

        dd($person);
    }

    #[Route('/add/contact', name: 'app_add_contact')]
    public function addContact(EntityManagerInterface $em): Response
    {
       $contact = new Contact();
       $contact->setFirstname('Johnny');
       $contact->setName('Bigoude');
       $contact->setSanitaryPass(false);
       $contact->setEmail('chuck@berry.com');
       $contact->setName('Bigoude');
       $contact->setPassword('moha');
        dump($contact);
       $em->persist($contact);
       $em->flush();

       dd($contact);
    }

    
    #[Route('/contacts', name: 'app_contact')] //c'est l'URL /
    public function contacts(EntityManagerInterface $em): Response
    {

        dump(Contact::class);

        //Select*from contact;
        $contacts = $em->getRepository(Contact::class)->findAll();
        dump($contacts);
        $contacts = $em->getRepository(Contact::class)->findBy([
            'password'=>'moha',
        ]);

        dump($contacts);

        $contact = $em->getRepository(Contact::class)->findOneBy([
            'id'=> 2,
        ]);

        dump($contact);



        dd($contacts);
    }
}