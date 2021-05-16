<?php

namespace App\Controller;

use App\Repository\AddRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AddRepository $repo, SessionInterface $session): Response
    {
        // $repo = $this->getDoctrine()->getRepository(Add::class);
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Permet d'afficher une annonce
     * 
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @param [type] $slug
     * @param AddRepository $repo
     * @return Response
     */
    public function show($slug, AddRepository $repo): Response
    {
        $ad = $repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }
}
