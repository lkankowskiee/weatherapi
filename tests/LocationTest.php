<?php

namespace App\Tests;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LocationTest extends KernelTestCase
{
    protected ?EntityManagerInterface $entityManager;

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

    /** @test */
    public function a_location_can_b_created_in_the_database()
    {
        $location = new Location();
        $location->setName('Test location');
        $location->setCountry('Test country');
        $this->entityManager->persist($location);

        $this->entityManager->flush();

        $locationRepository = $this->entityManager->getRepository(Location::class);

        $locationRecord = $locationRepository->findOneBy(['name' => 'Test location']);

        $this->assertSame('Test location', $locationRecord->getName());
        $this->assertSame('Test country', $locationRecord->getCountry());
    }
}