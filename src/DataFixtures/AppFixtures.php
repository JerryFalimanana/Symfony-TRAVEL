<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Add;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        for ($i = 1; $i <= 10; $i++) {
            $add = new Add;
    
            $add->setTitle("Titre de l'annonce n°$i")
                ->setSlug("titre-de-l-annonce-n-$i")
                ->setCoverImage("httm://placehold.it/1000x300")
                ->setIntroduction("Voici donc une introduction poir commencer")
                ->setContent("<p>Une contenue simple mais si riche que vous ne le pensez pas")
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));
    
            $manager->persist($add);
        }

        $manager->flush();
    }
}
