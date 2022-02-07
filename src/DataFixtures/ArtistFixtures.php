<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture
{
    private const firstNames = ['Axel', 'Ylona', 'Elie', 'David', 'Enzo', 'Julien', 'Louis', 'Gabriel'];
    private const lastNames = ['Goldman', 'Hallyday', 'Guetta', 'Sheeran', 'Obispo', 'Clerc', 'Mendes', 'Christol'];

    public const ARTIST_REF = 'ARTIST_REFERENCE_';

    public function load(ObjectManager $manager): void
    {
        // Max i at least 50 for BandFixtures Fixtures
        for ($i = 0; $i < 50; $i++) {
            $artist = new Artist();
            $firstname = self::firstNames[rand(0, count(self::firstNames) - 1)];
            $lastname = self::lastNames[rand(0, count(self::lastNames) - 1)];
            $name = $firstname . " " . $lastname;
            $artist->setName($name)
                ->setSceneName(str_replace(' ','_',$name) . "_scene")
                ->setBirthDate(\DateTime::createFromFormat("d/m/Y", rand(1,30) ."/" . rand(1,12) ."/" .rand(1960,2015)));

            $manager->persist($artist);
            $reference = self::ARTIST_REF . $i;
            $this->addReference($reference, $artist);
        }

        $manager->flush();
    }
}
