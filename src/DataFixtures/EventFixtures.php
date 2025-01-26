<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1; $i<= 10; $i++){

            $event = new Event();
            $event ->setTitle("Titre de l'événement n°$i");
            $event ->setContent("Contenude l'événement n°$i ");
            $event ->setImage("http://placehold.it/350x150");
            $event ->setDate(new \DateTime());
            $event ->setPlace("Lieu de l'événement n°$i");

            $manager ->persist($event);
                   

        }

        $manager->flush();
    }
}
