<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Forecast;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ForecastFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $refs = ['warszawa', 'london'];
        $date = new \DateTime();

        for ($i = 0; $i < 10001; $i++) {
            $forecast = new Forecast();
            $forecast->setLocation($this->getReference($refs[mt_rand(0, 1)]));
            $forecast->setDate(clone $date);
            $forecast->setAvgtempC(mt_rand(-200, 400) / 10);
            $forecast->setMaxtempC($forecast->getAvgtempC() + mt_rand(-50, 50) / 10);
            $forecast->setMintempC($forecast->getAvgtempC() - mt_rand(-50, 50) / 10);
            $forecast->setMaxwindKph(mt_rand(1, 2000) / 10);
            $forecast->setTotalprecipMm(mt_rand(0, 100) / 10);
            $forecast->setAvgvisKm(mt_rand(0, 500) / 10);
            $forecast->setAvghumidity(mt_rand(10, 100));
            $forecast->setDailyWillItRain((bool)mt_rand(0, 1));
            $forecast->setDailyChanceOfRain(mt_rand(10, 100));
            $forecast->setDailyWillItSnow((bool)mt_rand(0, 1));
            $forecast->setDailyChanceOfSnow(mt_rand(10, 100));
            $forecast->setConditionText('Light snow');
            $forecast->setConditionIcon('//cdn.weatherapi.com/weather/64x64/day/326.png');
            $forecast->setUv(1.0);
            $forecast->setHours(null); //TODO: put json here
            $manager->persist($forecast);
            unset($forecast);
            $date->add(new \DateInterval('P1D'));

            if ($i % 50 === 0) {
                $manager->flush();
            }
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
