<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Forecast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * @method Forecast|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forecast|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forecast[]    findAll()
 * @method Forecast[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForecastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forecast::class);
    }

    public function getCount(): int
    {
        $cache = new FilesystemAdapter();

        $cacheCount = $cache->getItem('forecast_count');
        if (!$cacheCount->isHit()) {
            $cacheCount->set(
                $this->createQueryBuilder('f')
                    ->select('count(f.id)')
                    ->getQuery()
                    ->getSingleScalarResult()
            );
            $cacheCount->expiresAfter(30);
            $cache->save($cacheCount);
        }
        return $cacheCount->get();
    }
}
