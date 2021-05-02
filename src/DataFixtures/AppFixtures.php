<?php

namespace App\DataFixtures;

use App\Entity\Add;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
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
