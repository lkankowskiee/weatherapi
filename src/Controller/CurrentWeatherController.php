<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Location;
use App\Form\CurrentWeatherType;
use App\Http\WeatherApiClient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/current', name: 'current.')]
class CurrentWeatherController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        Request $request,
        WeatherApiClient $weatherApiClient,
        RequestStack $requestStack,
        ManagerRegistry $doctrine
    ): Response {
        $session = $requestStack->getSession();
        $prevLocation = $session->get('prevLocation') ?? 'Gdańsk';

        $form = $this->createForm(CurrentWeatherType::class, ['q' => $prevLocation]);

        $form->handleRequest($request);
        $weatherData = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $locQuery = $form->getData()['q'];
            if ($locQuery) {
                $weatherData = $weatherApiClient->fetchCurrentWeather($locQuery);

                if ($weatherData['code'] === 200) {
                    $session->set('prevLocation', $weatherData['location']['name']);

                    // add new location to DB
                    $locationRepo = $doctrine->getRepository(Location::class);
                    if ($locationRepo->addLocationIfNotExists(
                        $weatherData['location']['name'],
                        $weatherData['location']['country']
                    )) {
                        $this->addFlash('success', 'New location saved!');
                    }
                } else {
                    $this->addFlash('warning', $weatherData['error'] . ' (code: ' . $weatherData['code'] . ')');
                }
            } else {
                $this->addFlash('warning', 'Location must not be empty');
            }
        }

        return $this->render('current_weather/index.html.twig', [
            'form'    => $form->createView(),
            'weather' => $weatherData
        ]);
    }
}
