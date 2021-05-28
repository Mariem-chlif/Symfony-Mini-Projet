<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GenreRepository;
use App\Repository\SongRepository;
use App\Repository\ArtisteRepository;

use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Genre;


class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

 /**
     * @Route("/songs_for_one_style/{id}", name="songs_for_one_style")
     */
    public function songs_for_one_style(Genre $genre): Response
    {
        return $this->render('home/songs_for_one_style.html.twig', [
            'style' => $genre,
        ]);
    }


    /**
     * @Route("/allsongs", name="allsongs")
     */
    public function allSongs(SongRepository $songRepository, Request $request, PaginatorInterface $paginator)
    {
        $songs= $paginator->paginate(
            $songRepository-> findAllSongs(),
            $request->query->getInt('page',1),
            4
        );
        return $this->render('home/allsongs.html.twig', [
            'songs' => $songs,
        ]);
    }

      /**
     * @Route("/allartiste", name="allartiste")
     */
    public function allArtiste(ArtisteRepository $artisteRepository, Request $request, PaginatorInterface $paginator)
    {
        $artistes= $paginator->paginate(
            $artisteRepository-> findAll(),
            $request->query->getInt('page',1),
            3
        );
        return $this->render('home/allartistes.html.twig', [
            'artistes' => $artistes,
        ]);
    }


     /**
     * @Route("/findbytitle", name="findbytitle")
     */
    public function findBytitle(SongRepository $songRepository, Request $request)
    {
            $songs=[];
            $title="";
            $etat= false;
            if($request->getMethod() == "POST")
            {
                $title= $request->request->get("title");
                $songs= $songRepository->findBytitle($title);
                $etat=true;
            }

         return $this->render('home/findbytitle.html.twig', [
            "songs"=>$songs,
            "title"=> $title,
            "etat"=> $etat
        ]);
    }



}
