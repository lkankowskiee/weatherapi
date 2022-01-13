<?php

declare(strict_types=1);

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApiClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $weatherApiUrl,
        private string $weatherApiKey
    ) {
    }

    public function fetchCurrentWeather(string $location): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->weatherApiUrl . '/current.json', [
                'query' => [
                    'key' => $this->weatherApiKey,
                    'q'   => $location
                ]
            ]);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'code'  => 500,
            ];
        }

        $responseStatusCode = $response->getStatusCode();

        if ($responseStatusCode !== 200) {
            return [
                'error' => $responseStatusCode === 401 ? 'Invalid API Key!' : 'Invalid location or other error',
                'code'  => $responseStatusCode,
            ];
        }

        return [...json_decode($response->getContent(), true), 'code' => 200];
    }

    public function fetchForecastWeather(string $location, int $days = 10): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->weatherApiUrl . '/forecast.json', [
                'query' => [
                    'key'  => $this->weatherApiKey,
                    'q'    => $location,
                    'days' => $days
                ]
            ]);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'code'  => 500,
            ];
        }

        if ($response->getStatusCode() !== 200) {
            $error = json_decode($response->getContent());
            return [
                'error' => $error->error->message,
                'code'  => $error->error->code,
            ];
        }

        return [...json_decode($response->getContent(), true), 'code' => 200];
    }
}