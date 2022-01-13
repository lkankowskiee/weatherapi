<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Forecast;
use App\Http\WeatherApiClient;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/update/{locationId}', name: 'update')]
    public function updateFromApi(
        string $locationId,
        LocationRepository $locationRepository,
        ManagerRegistry $doctrine,
        WeatherApiClient $weatherApiClient
    ): Response {
        $location = $locationRepository->findOneBy(['id' => $locationId]);
        if (!$location) {
            $this->addFlash('warning', 'Location not found in DB');
            return $this->redirect($this->generateUrl('forecast.index'));
        }

        $response = $weatherApiClient->fetchForecastWeather($location->getName(), 10);
        if ($response['code'] === 200) {
            $entityManager = $doctrine->getManager();

            foreach ($response['forecast']['forecastday'] as $f) {
                $forecast = new Forecast();
                $forecast->setLocation($location);
                $forecast->setDate(new \DateTime($f['date']));
                $forecast->setMaxtempC($f['day']['maxtemp_c']);
                $forecast->setMintempC($f['day']['mintemp_c']);
                $forecast->setAvgtempC($f['day']['avgtemp_c']);
                $forecast->setMaxwindKph($f['day']['maxwind_kph']);
                $forecast->setTotalprecipMm($f['day']['totalprecip_mm']);
                $forecast->setAvgvisKm($f['day']['avgvis_km']);
                $forecast->setAvghumidity($f['day']['avghumidity']);
                $forecast->setDailyWillItRain((bool)$f['day']['daily_will_it_rain']);
                $forecast->setDailyChanceOfRain($f['day']['daily_chance_of_rain']);
                $forecast->setDailyWillItSnow((bool)$f['day']['daily_will_it_snow']);
                $forecast->setDailyChanceOfSnow($f['day']['daily_chance_of_snow']);
                $forecast->setConditionText($f['day']['condition']['text']);
                $forecast->setConditionIcon($f['day']['condition']['icon']);
                $forecast->setUv($f['day']['uv']);
                $forecast->setHours($f['hour']);

                $entityManager->persist($forecast);

                unset($forecast);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Forecast for ' . $location->getName() . ' updated');
        } else {
            $this->addFlash('warning', $response['error'] . ' (code: ' . $response['error'] . ')');
        }

        return $this->redirect($this->generateUrl('forecast.index'));
    }

    #[Route('/hourly/{id}', name: 'hourly')]
    public function hourly(Forecast $forecast): Response
    {
        return $this->render('weather_forecast/hourly.html.twig', [
            'hourly' => $forecast->getHours()
        ]);
    }
}
