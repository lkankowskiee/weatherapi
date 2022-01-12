<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forecast', name: 'forecast.')]
class WeatherForecastController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ForecastRepository $repository): Response
    {
        $forecasts = $repository->findAll();

        return $this->render('weather_forecast/index.html.twig', [
            'forecasts' => $forecasts
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
