<?php

namespace App\DataFixtures;

use App\Entity\Organizer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganizerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $organizer = new Organizer();
        $organizer->setName("Paloma")
            ->setAddress("250 Chem. de l'Aérodrome, 30000 Nîmes")
            ->setInaugurationDate(\DateTime::createFromFormat("Y", "2012"))
            ->setPhoneNumber("04 11 94 00 10");
        $manager->persist($organizer);

        $manager->flush();
    }
}
