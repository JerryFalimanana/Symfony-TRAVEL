<?php

namespace App\Controller;

use App\Entity\Add;
use App\Form\AdType;
use App\Repository\AddRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/ads/{id}/edit", name = "admin_ads_edit")
     * 
     * @param Add $ad
     * @return Response
     */
    public function edit(Add $ad, Request $request, EntityManagerInterface $manager): Response 
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render('back_office/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }
}
