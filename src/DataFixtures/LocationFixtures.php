<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $location = new Location();
        $location->setName('Test location');
        $location->setCountry('Some country');
        $manager->persist($location);

        $manager->flush();

        $this->setReference('location_1', $location);
    }
}
