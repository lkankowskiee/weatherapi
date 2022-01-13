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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
        $prevLocation = $session->get('prevLocation') ?? 'Warszawa';

        $form = $this->createForm(CurrentWeatherType::class, ['q' => $prevLocation]);

        $form->handleRequest($request);
        $weatherData = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $weatherData = $this->fetchCurrentWeatherAndAddLocationIfNotExists(
                $form->getData()['q'],
                $session,
                $weatherApiClient,
                $doctrine
            );
        }

        return $this->render('current_weather/index.html.twig', [
            'form'    => $form->createView(),
            'weather' => $weatherData
        ]);
    }

    private function fetchCurrentWeatherAndAddLocationIfNotExists(
        ?string $locQuery,
        SessionInterface $session,
        WeatherApiClient $weatherApiClient,
        ManagerRegistry $doctrine
    ): array {
        if (!$locQuery) {
            $this->addFlash('warning', 'Location must not be empty');
            return [];
        }
        $weather = $weatherApiClient->fetchCurrentWeather($locQuery);

        if ($weather['code'] === 200) {
            $session->set('prevLocation', $weather['location']['name']);

            // add new location to DB
            $locationRepo = $doctrine->getRepository(Location::class);
            if ($locationRepo->addLocationIfNotExists($weather['location']['name'], $weather['location']['country'])) {
                $this->addFlash('success', 'New location saved!');
            }
        } else {
            $this->addFlash('warning', $weather['error'] . ' (code: ' . $weather['code'] . ')');
        }
        return $weather;
    }
}
