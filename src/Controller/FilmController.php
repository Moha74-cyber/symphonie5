<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Realisateur;
use App\Form\ActeurType;
use App\Form\FilmType;
use App\Form\RealisateurType;
use App\Repository\FilmRepository;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    #[Route('/film', name :'film_')]
class FilmController extends AbstractController
{
    #[Route('/acteur', name: 'acteur')]
    public function acteur(Request $request, EntityManagerInterface $em ,FileService $fileService): Response
    {
        $acteur = new Acteur();
        $form = $this->createForm(ActeurType::class, $acteur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
              //getData retourne l'entitée Film
            $acteur = $form->getData();

            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            $filename = $fileService->upload($file, $acteur);
            $acteur->setImage($filename); //  /upload/acteur/image.jpg

            $em->persist($acteur);
            $em->flush();

            return $this->redirectToRoute('acteur');
        }

        $entities = $em->getRepository(Acteur::class)->findAll();

        return $this->render('personne.html.twig', [
            'form' => $form->createView(),
            'entity_type' => 'Acteur',
            'entities' => $entities,
        ]);
    }

    #[Route('/realisateur', name: 'realisateur')]
    public function realisateur(Request $request, EntityManagerInterface $em, FileService $fileService): Response
    {
        $realisateur = new Realisateur();
        $form = $this->createForm(RealisateurType::class, $realisateur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $realisateur = $form->getData();

            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            $filename = $fileService->upload($file, $realisateur);
            $realisateur->setImage($filename); //  /upload/acteur/image.jpg

            $em->persist($realisateur);
            $em->flush();

            return $this->redirectToRoute('realisateur');
        }

        $entities = $em->getRepository(Realisateur::class)->findAll();

        return $this->render('personne.html.twig', [
            'form' => $form->createView(),
            'entity_type' => 'Réalisateur',
            'entities' => $entities,
        ]);
    }

    #[Route('/', name: 'film')]
    public function film(Request $request, EntityManagerInterface $em, FileService $fileService): Response
    {   
        //$user = $this->getUser();

    //dd($user->getRloes());

    /*if (!$this->isGranted('ROL_USER')){
        return $this->redirectToRoute('contact_list');
    }*/

    

        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //getData retourne l'entitée Film
            $film = $form->getData();

            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            $filename = $fileService->upload($file, $film);
            $film->setImage($filename); //  /upload/film/image.jpg

            $em->persist($film);
            $em->flush();

            return $this->redirectToRoute('film_film');
        }

        $films = $em->getRepository(Film::class)->findAll();

        return $this->render('film/index.html.twig', [
            'form' => $form->createView(),
            'films' => $films,
        ]);
    }
     #[Route('/search', name: 'search')]
    public function search(): Response
    {
        $form = $this->createFormBuilder()
            ->add('strSearch',TextType::class,[
                'label'=> 'rechercher',
                'required' => 'false',

            ])
            ->add('dateDebut',DateType::class,[
                'widget'=> 'single_text',
                'required' => 'false',
            ])
            ->add('dateFin',DateType::class,[
                 'widget'=> 'single_text',
                'required' => 'false',
            ])
            ->add('acteur',EntityType::class,[
                'class'=>Acteur::class,
                'choice_label' => 'fullname',

            ])
            ->add('submit', SubmitType::class, ['label'=>'chercher'])
            ->getForm();

            return $this->render('film/film.html.twig',[
                'form' => $form->createView(),
            ]);
    }

       #[Route('/search/response', name: 'search_response')]
    public function searchResponse(Request $request,FilmRepository $filmRepository): Response
    {
        $form = $request->request->all();

        $films = $filmRepository->search($form['form']);
        
        $view = $this->renderView('film/_search.html.twig', [
            'films' => $films,
        ]);


        return $this->json([
            'view'=> $view,
        ]);
        
      

    }
}