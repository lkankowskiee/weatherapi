<?php

namespace App\Tests\integration;

use App\Http\WeatherApiClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WeatherApiClientTest extends KernelTestCase
{
    /** @test */
    public function weather_api_client_returns_correct_current_data()
    {
        // setup
        $weatherApiClient = $this->getContainer()->get(WeatherApiClient::class);

        $response = $weatherApiClient->fetchCurrentWeather('Warszawa');

        $this->assertSame(200, $response['code']);
        $this->assertSame('Warszawa', $response['location']['name']);
        $this->assertSame('Poland', $response['location']['country']);
        $this->assertIsFloat($response['current']['temp_c']);
        $this->assertIsFloat($response['current']['feelslike_c']);
        $this->assertIsString($response['current']['condition']['text']);
        $this->assertIsString($response['current']['condition']['icon']);
        $this->assertIsFloat($response['current']['wind_kph']);
        $this->assertIsString($response['current']['wind_dir']);
        $this->assertIsFloat($response['current']['pressure_mb']);
        $this->assertIsInt($response['current']['humidity']);
        $this->assertGreaterThanOrEqual(0, $response['current']['humidity']);
        $this->assertLessThanOrEqual(100, $response['current']['humidity']);
        $this->assertIsFloat($response['current']['vis_km']);
        $this->assertIsFloat($response['current']['uv']);
   }

    /** @test */
    public function weather_api_client_returns_correct_forecast_data()
    {
        // setup
        $weatherApiClient = $this->getContainer()->get(WeatherApiClient::class);

        $response = $weatherApiClient->fetchForecastWeather('Warszawa', 2);

        $this->assertSame(200, $response['code']);
        $this->assertSame('Warszawa', $response['location']['name']);
        $this->assertSame('Poland', $response['location']['country']);

        $f = $response['forecast']['forecastday'];

        $this->assertSame(2, count($f));
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $f[0]['date']);
        $this->assertIsFloat($f[0]['day']['maxtemp_c']);
        $this->assertIsFloat($f[0]['day']['mintemp_c']);
        $this->assertIsFloat($f[0]['day']['avgtemp_c']);
        $this->assertIsFloat($f[0]['day']['maxwind_kph']);
        $this->assertIsFloat($f[0]['day']['totalprecip_mm']);
        $this->assertIsFloat($f[0]['day']['avgvis_km']);
        $this->assertIsFloat($f[0]['day']['avghumidity']);
        $this->assertIsInt($f[0]['day']['daily_will_it_rain']);
        $this->assertIsInt($f[0]['day']['daily_chance_of_rain']);
        $this->assertIsInt($f[0]['day']['daily_will_it_snow']);
        $this->assertIsInt($f[0]['day']['daily_chance_of_snow']);
        $this->assertIsString($f[0]['day']['condition']['text']);
        $this->assertIsString($f[0]['day']['condition']['icon']);
        $this->assertIsFloat($f[0]['day']['uv']);
        $this->assertSame(24, count($f[0]['hour']));
    }
}