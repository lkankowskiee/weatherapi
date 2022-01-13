<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $location1 = new Location();
        $location1->setName('Warszawa');
        $location1->setCountry('Poland');
        $manager->persist($location1);

        $location2 = new Location();
        $location2->setName('London');
        $location2->setCountry('UK');
        $manager->persist($location2);

        $manager->flush();

        $this->setReference('warszawa', $location1);
        $this->setReference('london', $location2);
    }
}
