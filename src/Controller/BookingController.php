<?php

namespace App\Controller;

use App\Entity\Add;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="book_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Add $ad, Request $request, EntityManagerInterface $manager): Response
    {
        $booking = new Booking;
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $booking->setBooker($user)
                    ->setAd($ad);
            
            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('booking_show', [
                'id' => $booking->getId(),
                'withAlert' => true
            ]);
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une réservation
     *
     * @Route("/booking/{id}", name = "booking_show")
     * 
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking): Response {
        
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }
}
