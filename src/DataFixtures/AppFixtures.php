<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Add;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for ($i = 1; $i <= 10; $i++) {
            $add = new Add;

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = "<p>" . join('</p><p>', $faker->paragraphs(5)) . "</p>";
    
            $add->setTitle($title)
                ->setCoverImage("https://picsum.photos/1000/300")
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));
    
            for ($j = 1; $j <= rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl("https://picsum.photos/1000/300")
                      ->setCaption($faker->sentence())
                      ->setAd($add);
                
                $manager->persist($image);
            }
            
            $manager->persist($add);
        }

        $manager->flush();
    }
}
