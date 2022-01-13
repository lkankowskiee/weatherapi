<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrentWeatherControllerTest extends WebTestCase
{
    /** @test */
    public function check_if_form_has_a_default_value(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/current/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Current weather');
        $this->assertSelectorExists('input#current_weather_q[value=Warszawa]');
    }
}
