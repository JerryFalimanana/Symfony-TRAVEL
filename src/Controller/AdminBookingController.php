<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo): Response
    {
        return $this->render('back_office/booking/index.html.twig', [
            'bookings' => $repo->findAll(),
        ]);
    }

    /**
     *  Permet de modifier une réservation
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * 
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $manager->persist($booking); comme la réservation est déjà reconu par le manager
            $manager->flush(); // on utilise tout simplement cette ligne
            $this->addFlash(
                'success',
                "La réservation n°{$booking->getId()} est bien modifiée"
            );

            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('back_office/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking,
        ]);
    }
}
