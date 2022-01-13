<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        parent::__construct($registry, Location::class);
    }

    public function addLocationIfNotExists(string $locationName, string $locationCountry): bool
    {
        $location = $this->findOneBy(['name' => $locationName]);
        if (!$location) {
            $entityManager = $this->registry->getManager();

            $location = new Location();
            $location->setName($locationName);
            $location->setCountry($locationCountry);

            $entityManager->persist($location);
            $entityManager->flush();
            return true;
        }
        return false;
    }
}
