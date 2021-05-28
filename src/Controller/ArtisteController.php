<?php

namespace App\Controller;  // create controller 

//importation 
use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\HttpFoundation\File\File;

// Annotation routes

/**
 * @Route("/artiste")
 * 
 */
class ArtisteController extends AbstractController    //create Controller class
{
    /**
     * @Route("/", name="artiste_index", methods={"GET"})   //  specify  route and method 
     */
    public function index(ArtisteRepository $artisteRepository): Response    // create method index 
    {
        return $this->render('artiste/index.html.twig', [   // render twig templeate artiste/index.html.twig ( Pass it a articles variable so we can use it in Twig)
            'artistes' => $artisteRepository->findAll(),   // look for *all* Artiste objects
        ]);
    }

    /**
     * @Route("/new", name="artiste_new", methods={"GET","POST"})  //  specify  route and method 
     */
    public function new(Request $request): Response    
    {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            if($artiste->getArtisteImage()=="")
            $artiste->setArtisteImage("no_image_jpg");
             else 
            {
            $file= $artiste->getArtisteImage();
            $fileName= md5(uniqid()).'.'.$file ->guessExtension();
            $file->move($this->getParameter('images_directory'), $fileName);
            $artiste->setArtisteImage($fileName);
            }
                          //fetch the EntityManager via $this->getDoctrine()
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($artiste);  // save the Artiste (no queries yet)
            $entityManager->flush();    // actually executes the queries 

            return $this->redirectToRoute('artiste_index');
        }

        return $this->render('artiste/new.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="artiste_show", methods={"GET"})  //  specify  route and method 
     */
    public function show(Artiste $artiste): Response    

    {
        return $this->render('artiste/show.html.twig', [
            'artiste' => $artiste,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="artiste_edit", methods={"GET","POST"})   //  specify  route and method 
     */
    public function edit(Request $request, Artiste $artiste): Response
    {

// to edit artiste we  fetching the object from Doctrine , modified then calling flush() on the entity manager.

        $name= $artiste->getArtisteImage();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            if($artiste->getArtisteImage()=="")
            $artiste->setArtisteImage($name);
             else 
             {
         $file= new File($artiste->getArtisteImage());
         $fileName= md5(uniqid()).'.'.$file ->guessExtension();
         $file->move($this->getParameter('images_directory'), $fileName);
         $artiste->setArtisteImage($fileName);

                   if($name !="no_image_jpg")
                     if(file_exists("uploads/images/".$name))
                     unlink("uploads/images/".$name);
             }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('artiste_index');
        }

        return $this->render('artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="artiste_delete", methods={"DELETE"})   //  specify  route and method 
     */
    public function delete(Request $request, Artiste $artiste): Response
    {

        // delete artiste with remove() then call  flush method 
        if ($this->isCsrfTokenValid('delete'.$artiste->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($artiste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('artiste_index');
    }
}
