<?php

namespace App\Controller;

use App\Entity\Add;
use App\Form\AdType;
use App\Repository\AddRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
    public function new(): Response
    {
        $ad = new Add();

        // $form = $this->createFormBuilder($ad)
        //              ->add('title')
        //              ->add('introduction')
        //              ->add('content')
        //              ->add('rooms')
        //              ->add('price')
        //              ->add('coverImage')
        //              ->add('save', SubmitType::class, [
        //                  'label' => 'Envoyer l\'annonce',
        //                  'attr' => [
        //                      'class' => 'btn btn-primary',
        //                      'style' => 'border-radius: 10px',
        //                  ]
        //              ])
        //              ->getForm();

        $form = $this->createForm(AdType::class, $ad);

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
