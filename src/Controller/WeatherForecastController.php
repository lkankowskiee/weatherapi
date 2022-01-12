<?php

namespace App\Controller;

use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/forecast', name: 'forecast.')]
class WeatherForecastController extends AbstractController
{
    #[Route('/{page?}', name: 'index')]
    public function index(?string $page, ForecastRepository $repository): Response
    {
        $page = !$page && !is_int($page) ? 1 : $page;
        $offset = max(($page - 1), 0) * 10;

        $forecasts = $repository->findBy([], ['id' => 'ASC'], 10, $offset);

        $count = $repository->getCount();

        $maxPages = ceil($count / 10);

        return $this->render('weather_forecast/index.html.twig', [
            'forecasts' => $forecasts,
            'pager'     => [
                'current'  => $page,
                'previous' => max($page - 1, 1),
                'next'     => min($page + 1, $maxPages),
                'last'     => $maxPages,
            ]
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateFromApi(string $id, LocationRepository $locationRepository, ManagerRegistry $doctrine): Response
    {
        $location = $locationRepository->findOneBy(['id' => $id]);
        if (!$location) {
            $this->addFlash('warning', 'Location not found in DB');
            return $this->redirect($this->generateUrl('forecast.index'));
        }
//        $entityManager = $doctrine->getManager();

//        $entityManager->persist($location);
//        $entityManager->flush();

        $this->addFlash('success', 'Forecast for ' . $location->getName() . ' updated');

        return $this->redirect($this->generateUrl('forecast.index'));
    }
}
