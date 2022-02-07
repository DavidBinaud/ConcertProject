<?php

namespace App\DataFixtures;

use App\Entity\Concert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConcertFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $concert = new Concert();
        $concert->setCapacity(50)
            ->setName("Loolapalooza")
            ->setDate(\DateTime::createFromFormat("d/m/Y", "25/12/2021"))
            ->setVenue($this->getReference(VenueFixtures::VENUE_REF . 0))
            ->addBand($this->getReference(BandFixtures::BAND_REF . 0))
            ->addBand($this->getReference(BandFixtures::BAND_REF . 1));
        $manager->persist($concert);

        $concert = new Concert();
        $concert->setCapacity(500)
            ->setName("Les Déferlantes")
            ->setDate(\DateTime::createFromFormat("d/m/Y", "06/01/2022"))
            ->setVenue($this->getReference(VenueFixtures::VENUE_REF . 0))
            ->addBand($this->getReference(BandFixtures::BAND_REF . 2));
        $manager->persist($concert);

        $concert = new Concert();
        $concert->setCapacity(500)
            ->setName("Les Estivales")
            ->setDate(\DateTime::createFromFormat("d/m/Y", "07/07/2022"))
            ->setVenue($this->getReference(VenueFixtures::VENUE_REF . 1))
            ->addBand($this->getReference(BandFixtures::BAND_REF . 2));
        $manager->persist($concert);

        $concert = new Concert();
        $concert->setCapacity(500)
            ->setName("Rock en Scène")
            ->setDate(\DateTime::createFromFormat("d/m/Y", "17/07/2022"))
            ->setVenue($this->getReference(VenueFixtures::VENUE_REF . 4))
            ->addBand($this->getReference(BandFixtures::BAND_REF . 7))
            ->addBand($this->getReference(BandFixtures::BAND_REF . 5))
            ->addBand($this->getReference(BandFixtures::BAND_REF . 8));
        $manager->persist($concert);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BandFixtures::class,
            VenueFixtures::class
        ];
    }
}
