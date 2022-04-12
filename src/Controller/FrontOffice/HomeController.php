<?php

namespace App\Controller\FrontOffice;

use App\Repository\AddRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AddRepository $adRepo, UserRepository $userRepo): Response
    {
        return $this->render('front_office/home/index.html.twig', [
            'ads' => $adRepo->findBestAds(3),
            'users' => $userRepo->findBestUsers(2)
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
