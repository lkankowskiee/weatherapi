<?php

namespace App\DataFixtures;

use App\Entity\Forecast;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ForecastFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $date = new \DateTime();

        for($i = 0; $i < 1001; $i++)
        {
            $forecast = new Forecast();
            $forecast->setLocation($this->getReference('location_1'));
            $forecast->setDate(clone $date);
            $forecast->setMaxtempC(0.1);
            $forecast->setMintempC(-2.6);
            $forecast->setAvgtempC(-1.2);
            $forecast->setMaxwindKph(8.3);
            $forecast->setTotalprecipMm(0.2);
            $forecast->setAvgvisKm(9.8);
            $forecast->setAvghumidity(mt_rand(10, 100));
            $forecast->setDailyWillItRain(true);
            $forecast->setDailyChanceOfRain(72);
            $forecast->setDailyWillItSnow(0);
            $forecast->setDailyChanceOfSnow(7);
            $forecast->setConditionText('Light snow');
            $forecast->setConditionIcon('//cdn.weatherapi.com/weather/64x64/day/326.png');
            $forecast->setUv(1.0);
            $forecast->setHours(null); //TODO: put json here
            $manager->persist($forecast);
            unset($forecast);
            $date->add(new \DateInterval('P1D'));
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LocationFixtures::class
        ];
    }
}
