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
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AddRepository $repo, $page): Response // $page prendra automatiquement la valeur 1 d'après l'expression régulière
    {
        $limit = 10;
        $start = $page * $limit - $limit;

        return $this->render('back_office/ad/index.html.twig', [
            'ads' => $repo->findBy([], [], $limit, $start),
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

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/ads/{id}/delete", name = "admin_ads_delete")
     * 
     * @param Add $ad
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Add $ad, EntityManagerInterface $manager)
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà une réservation."
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
