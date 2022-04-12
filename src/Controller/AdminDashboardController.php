<?php

namespace App\Controller;

use App\Service\Statistics;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, Statistics $statistics): Response
    {
        $stats = $statistics->getStats();
        $bestAds = $statistics->getAdsStats('DESC');
        $worstAds = $statistics->getAdsStats('ASC');

        return $this->render('back_office/dashboard/index.html.twig', [
            // 'stats' => [
            //     'users' => $users,
            //     'ads' => $ads,
            //     'bookings' => $bookings,
            //     'comments' => $comments,
            // ]
            //'stats' => compact('users', 'ads', 'bookings', 'comments'), cette fonction compact() permet de retourner un tableau de valeurs même que les clés
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds,
        ]);
    }
}
