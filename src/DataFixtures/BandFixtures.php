<?php

namespace App\DataFixtures;

use App\Entity\Band;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BandFixtures extends Fixture implements DependentFixtureInterface
{
    private const bandNames = ['The Sliding Stones', 'Tropicana', 'System of a Up', 'GSM', 'FrancoGallois', 'Dreaming Birds', 'JustWater', 'Reine', 'Les petits pois aux yeux noirs'];

    public const BAND_REF = 'BAND_REFERENCE_';

    public function load(ObjectManager $manager): void
    {
        for($j = 0; $j < count(self::bandNames); $j++) {
            $band = new Band();
            $band->setName(self::bandNames[$j]);
            for ($i = 5*$j; $i < rand((5*($j)), 5*($j+1)); $i++) {
                $band->addArtist($this->getReference(ArtistFixtures::ARTIST_REF . $i));
            }
            $band->setCreationDate(\DateTime::createFromFormat("d/m/Y", rand(1,30) ."/" . rand(1,12) ."/" .rand(1960,2015)));
            $manager->persist($band);
            $this->addReference(self::BAND_REF . $j, $band);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArtistFixtures::class,
        ];
    }
}
