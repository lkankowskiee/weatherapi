<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Forecast;
use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ForecastTest extends KernelTestCase
{
    /** @test */
    public function a_forecast_can_b_created_in_the_database(): void
    {
        $location = new Location();
        $location->setName('Test location');
        $location->setCountry('Test country');
        $this->entityManager->persist($location);

        $forecast = new Forecast();
        $forecast->setLocation($location);
        $forecast->setDate((new \DateTime())->setTime(0, 0));
        $forecast->setAvgtempC(5.5);
        $forecast->setMaxtempC(10.5);
        $forecast->setMintempC(1.5);
        $forecast->setMaxwindKph(22.5);
        $forecast->setTotalprecipMm(12.5);
        $forecast->setAvgvisKm(5.5);
        $forecast->setAvghumidity(55.5);
        $forecast->setDailyWillItRain(false);
        $forecast->setDailyChanceOfRain(5.0);
        $forecast->setDailyWillItSnow(false);
        $forecast->setDailyChanceOfSnow(0.0);
        $forecast->setConditionText('Light snow');
        $forecast->setConditionIcon('url');
        $forecast->setUv(1.5);
        $forecast->setHours(null);
        $this->entityManager->persist($forecast);

        $this->entityManager->flush();

        $forecastRepository = $this->entityManager->getRepository(Forecast::class);

        $forecastRecord = $forecastRepository->findOneBy(['id' => $forecast->getId()]);

        $this->assertEquals($location, $forecastRecord->getLocation());
        $this->assertEquals((new \DateTime())->setTime(0, 0), $forecastRecord->getDate());
        $this->assertSame(5.5, $forecastRecord->getAvgtempC());
        $this->assertSame(10.5, $forecastRecord->getMaxtempC());
        $this->assertSame(1.5, $forecastRecord->getMintempC());
        $this->assertSame(22.5, $forecastRecord->getMaxwindKph());
        $this->assertSame(12.5, $forecastRecord->getTotalprecipMm());
        $this->assertSame(5.5, $forecastRecord->getAvgvisKm());
        $this->assertSame(55.5, $forecastRecord->getAvghumidity());
        $this->assertSame(false, $forecastRecord->getDailyWillItRain());
        $this->assertSame(5.0, $forecastRecord->getDailyChanceOfRain());
        $this->assertSame(false, $forecastRecord->getDailyWillItSnow());
        $this->assertSame(0.0, $forecastRecord->getDailyChanceOfSnow());
        $this->assertSame('Light snow', $forecastRecord->getConditionText());
        $this->assertSame('url', $forecastRecord->getConditionIcon());
        $this->assertSame(1.5, $forecastRecord->getUv());
        $this->assertNull($forecastRecord->getHours());
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}