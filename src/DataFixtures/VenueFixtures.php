<?php

namespace App\DataFixtures;

use App\Entity\Venue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VenueFixtures extends Fixture
{
    public const VENUE_REF = 'VENUE_REFERENCE_';

    public function load(ObjectManager $manager): void
    {
        for ($j = 0; $j < 5; $j++) {
            $venue = new Venue();
            $venue->setName("Paloma" . "_" . $j);
            $manager->persist($venue);

            $this->addReference(self::VENUE_REF . $j, $venue);
        }

        $manager->flush();
    }
}
