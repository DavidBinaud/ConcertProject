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
        foreach (["Loolapalooza", "Electrobeach", "Rock en scène", "Les Vieilles Charrues"] as $a){
            for ($i = 20; $i < 30; $i++) {
                $concert = new Concert();
                $concert->setCapacity(50)
                    ->setName("$a 20$i")
                    ->setDate(\DateTime::createFromFormat("d/m/Y", rand(1,28) . '/' . rand(1,12) . "/20" . $i))
                    ->setVenue($this->getReference(VenueFixtures::VENUE_REF . rand(0,4)));
                for ($j = 0; $j < rand(0,3); $j++) {
                    $concert->addBand($this->getReference(BandFixtures::BAND_REF . rand(0,8)));
                }
                $manager->persist($concert);
            }
        }



        $concert = new Concert();
        $concert->setCapacity(500)
            ->setName("Woodstock")
            ->setDate(\DateTime::createFromFormat("d/m/Y", "06/01/1969"))
            ->setVenue($this->getReference(VenueFixtures::VENUE_REF . 0))
            ->addBand($this->getReference(BandFixtures::BAND_REF . 2));
        $manager->persist($concert);

        for ($i = 10; $i < 15; $i++) {
            $concert = new Concert();
            $concert->setCapacity(500)
                ->setName("Les Estivales 20$i")
                ->setDate(\DateTime::createFromFormat("d/m/Y", rand(1,28) . '/' . rand(1,12) . "/20" . $i))
                ->setVenue($this->getReference(VenueFixtures::VENUE_REF . rand(0,4)));
            for ($j = 0; $j < rand(0,3); $j++) {
                $concert->addBand($this->getReference(BandFixtures::BAND_REF . rand(0,8)));
            }
            $manager->persist($concert);
        }

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
