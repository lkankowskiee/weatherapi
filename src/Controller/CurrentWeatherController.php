<?php

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
    #[Route('/{q?}', name: 'index')]
    public function index(Request $request, WeatherApiClient $weatherApiClient, RequestStack $requestStack, ManagerRegistry $doctrine): Response
    {
        $session = $requestStack->getSession();
        $prevLocation = $session->get('prevLocation') ?? 'Gdańsk';

        $form = $this->createForm(CurrentWeatherType::class, ['q' => $prevLocation], [
            //'method' => 'GET'
        ]);

        $form->handleRequest($request);
        $weatherData = [];

        if ($form->isSubmitted()) {
            $locQuery = $form->getData()['q'];
            $session->set('prevLocation', $locQuery);

            $weatherData = $weatherApiClient->fetchCurrentWeather($locQuery);
            if ($weatherData['code'] === 200) {
                // add new location to DB - should be moved somewhere else
                $locationRepo = $doctrine->getRepository(Location::class);
                $location = $locationRepo->findOneBy(['name' => $weatherData['location']['name']]);
                if (!$location) {
                    $entityManager = $doctrine->getManager();
                    $location = new Location();
                    $location->setName($weatherData['location']['name']);
                    $location->setCountry($weatherData['location']['country']);
                    $entityManager->persist($location);
                    $entityManager->flush();
                    $this->addFlash('success', 'New location saved!');
                }
            }
        }

        return $this->render('current_weather/index.html.twig', [
            'form' => $form->createView(),
            'weather' => $weatherData
        ]);
    }
}
