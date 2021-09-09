<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\component\Form\AbstractType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact/list', name: 'contact_list')]
    public function index(EntityManagerInterface $em): Response
    {
        $contacts = $em->getRepository(Contact::class)->findAll();

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
        ]);

    }  
    #[Route('/contact/detail/{id}', name: 'contact_detail')]
    public function detail(ContactRepository $contactRepo,$id): Response
    {
        //oncharge le repo avec la methode magique findOneById
        $contact = $contactRepo->findOneById($id);
        //on renvoie a la vue
        return $this->render('contact/detail.html.twig',[
            'contact'=> $contact,
        ]);
    }

        #[Route('/contact/add', name: 'contact_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        //permet de retrouver toutes les variables $post
        $params = $request->request->all();

        dump($params);

        //si la tableau n'est pas vid ca veut dire les données ont été envoyé via le formulaire
        if(!empty($params)){
             $contact = new Contact();
       $contact->setFirstname($params['firstname']);
       $contact->setEmail($params['email']);
       $contact->setName($params['name']);
       $contact->setPassword($params['password']);

   
       $contact->setSanitaryPass(isset($params['sanitaryPass']));

        
        //recuper $post['name']
        // $request->request->get('name');

        //permet de retrouver toutes les variables $post
         //$request->query->all();
        $em->persist($contact);
        $em->flush();

         return $this->redirectToRoute('contact_list');
     }
        //on renvoie a la vue
        return $this->render('contact/add.html.twig',[
            
        ]);
    }

    
        #[Route('/contact/delete/{id}', name: 'contact_delete')]
    public function delete(Contact $contact, EntityManagerInterface $em): Response
    {
      $em->remove($contact);
        $em->flush();

        return $this->redirectToRoute('contact_list');

     }       
        #[Route('/contact/update/{id}', name: 'contact_update')]
    public function update(Contact $contact, EntityManagerInterface $em,Request $request): Response
    {
                $form = $this->createForm(ContactType::class,$contact);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()){
                    //sauvegarder les modif faites dans $contact en bdd
                    $em->flush();

                    return $this->redirectToRoute('contact_list');
                }

                return $this->render('contact/update.html.twig', [
                     'contact' => $contact,
                     'form'=> $form->createView(),
                ]);
               


    }
}