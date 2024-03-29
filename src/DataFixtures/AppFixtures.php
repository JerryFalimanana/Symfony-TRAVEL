<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Add;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $adminRole = new Role;
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User;
        $adminUser->setFirstName('Jerry')
                  ->setLastName('Falimanana')
                  ->setEmail('jerry@symfony.com')
                  ->setIntroduction($faker->sentence())
                  ->setDescription("<p>" . join('</p><p>', $faker->paragraphs(3)) . "</p>")
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://avatars.githubusercontent.com/u/73733746?v=4')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // gerer les utilisateurs
        $users = [];

        $genres = ['male', 'female'];

        for ($i = 1; $i <= 20; $i++) {
            $user = new User;

            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription("<p>" . join('</p><p>', $faker->paragraphs(3)) . "</p>")
                 ->setHash($hash)
                 ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // gerer les annonces
        for ($i = 1; $i <= 30; $i++) {
            $ad = new Add;

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = "<p>" . join('</p><p>', $faker->paragraphs(5)) . "</p>";

            $user = $users[mt_rand(0, count($users) - 1)];
    
            $ad->setTitle($title)
                ->setCoverImage("https://picsum.photos/1000/300")
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
    
            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl("https://picsum.photos/1000/300")
                      ->setCaption($faker->sentence())
                      ->setAd($ad);
                
                $manager->persist($image);
            }

            // gestion des résérvations
            for ($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking;
                
                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                $duration = mt_rand(3, 10);
                $endDate = (clone $startDate)->modify("+$duration days");

                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setCreatedAt($createdAt)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setAmount($amount)
                        ->setComment($comment);

                $manager->persist($booking);

                // Géstion des commentaires
                if (mt_rand(0, 1)) {
                    $comment = new Comment;
                    $comment->setContent($faker->paragraph())
                            ->setRating(mt_rand(1, 5))
                            ->setAuthor($booker)
                            ->setAd($ad);

                    $manager->persist($comment);
                }
            }
            
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
