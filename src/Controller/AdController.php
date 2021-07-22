<?php

namespace App\Controller;

use App\Entity\Add;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AddRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * Permer de créer une nouvelle annonce
     * 
     * @Route("/ads/new", name="ads_new")
     *
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $ad = new Add();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> à bien été enregistrée"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView(),
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
    public function show(Add $ad): Response
    {
        // $ad = $repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }
}
