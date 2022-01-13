<?php

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApiClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $weatherApiUrl,
        private string $weatherApiKey
    )
    {
    }

    public function fetchCurrentWeather(string $location): array
    {
        $response = $this->httpClient->request('GET', $this->weatherApiUrl . '/current.json', [
            'query' => [
                'key' => $this->weatherApiKey,
                'q' => $location
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            return ['error' => 'API Client Error', 'code' => 400];
        }

        return [...json_decode($response->getContent(), true), 'code' => 200];
    }

    public function fetchForecastWeather(string $location, int $days = 10): array
    {
        $response = $this->httpClient->request('GET', $this->weatherApiUrl . '/forecast.json', [
            'query' => [
                'key'  => $this->weatherApiKey,
                'q'    => $location,
                'days' => $days
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            return ['error' => 'API Client Error', 'code' => 400];
        }

        return [...json_decode($response->getContent(), true), 'code' => 200];
    }
}