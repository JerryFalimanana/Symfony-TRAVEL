<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $nombre = [1,2,3,4,5,6,7,8,9];

        return $this->render('front_office/home/index.html.twig', [
            'chiffre' => $nombre,
        ]);
    }

    /**
     * Fonction avec route parametré
     * 
     * @Route("/hello/{prenom}", name="test")
     *
     * @param [type] $prenom
     * @return Response
     */
    public function hello($prenom): Response
    {
        return new Response("Bonjour " . $prenom);
    }
}
