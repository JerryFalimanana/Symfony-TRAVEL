<?php

namespace App\Controller;

use App\Repository\AddRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AddRepository $repo): Response
    {
        return $this->render('back_office/ad/index.html.twig', [
            'ads' => $repo->findAll(),
        ]);
    }
}
