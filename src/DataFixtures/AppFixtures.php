<?php

namespace App\DataFixtures;

use App\Entity\Forecast;
use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $location = new Location();
        $location->setName('Test location');
        $location->setCountry('Some country');
        $manager->persist($location);

        for($i = 0; $i < 1000; $i++)
        {
            $forecast = new Forecast();
            $forecast->setLocation($location);
            $forecast->setDate(new \DateTime());
            $forecast->setMaxtempC(0.1);
            $forecast->setMintempC(-2.6);
            $forecast->setAvgtempC(-1.2);
            $forecast->setMaxwindKph(8.3);
            $forecast->setTotalprecipMm(0.2);
            $forecast->setAvgvisKm(9.8);
            $forecast->setAvghumidity(89.0);
            $forecast->setDailyWillItRain(true);
            $forecast->setDailyChanceOfRain(72);
            $forecast->setDailyWillItSnow(0);
            $forecast->setDailyChanceOfSnow(7);
            $forecast->setConditionText('Light snow');
            $forecast->setConditionIcon('//cdn.weatherapi.com/weather/64x64/day/326.png');
            $forecast->setUv(1.0);
            $forecast->setHours(null);
            $manager->persist($forecast);
            unset($forecast);
        }

        $manager->flush();
    }
}
