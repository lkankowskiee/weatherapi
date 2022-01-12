<?php

namespace App\Entity;

use App\Repository\ForecastRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForecastRepository::class)]
class Forecast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'forecasts')]
    #[ORM\JoinColumn(nullable: false)]
    private $location;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\Column(type: 'float')]
    private $maxtemp_c;

    #[ORM\Column(type: 'float')]
    private $mintemp_c;

    #[ORM\Column(type: 'float')]
    private $avgtemp_c;

    #[ORM\Column(type: 'float')]
    private $maxwind_kph;

    #[ORM\Column(type: 'float')]
    private $totalprecip_mm;

    #[ORM\Column(type: 'float')]
    private $avgvis_km;

    #[ORM\Column(type: 'float')]
    private $avghumidity;

    #[ORM\Column(type: 'boolean')]
    private $daily_will_it_rain;

    #[ORM\Column(type: 'float')]
    private $daily_chance_of_rain;

    #[ORM\Column(type: 'boolean')]
    private $daily_will_it_snow;

    #[ORM\Column(type: 'float')]
    private $daily_chance_of_snow;

    #[ORM\Column(type: 'string', length: 50)]
    private $condition_text;

    #[ORM\Column(type: 'string', length: 200)]
    private $condition_icon;

    #[ORM\Column(type: 'float')]
    private $uv;

    #[ORM\Column(type: 'json', nullable: true)]
    private $hours = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMaxtempC(): ?float
    {
        return $this->maxtemp_c;
    }

    public function setMaxtempC(float $maxtemp_c): self
    {
        $this->maxtemp_c = $maxtemp_c;

        return $this;
    }

    public function getMintempC(): ?float
    {
        return $this->mintemp_c;
    }

    public function setMintempC(float $mintemp_c): self
    {
        $this->mintemp_c = $mintemp_c;

        return $this;
    }

    public function getAvgtempC(): ?float
    {
        return $this->avgtemp_c;
    }

    public function setAvgtempC(float $avgtemp_c): self
    {
        $this->avgtemp_c = $avgtemp_c;

        return $this;
    }

    public function getMaxwindKph(): ?float
    {
        return $this->maxwind_kph;
    }

    public function setMaxwindKph(float $maxwind_kph): self
    {
        $this->maxwind_kph = $maxwind_kph;

        return $this;
    }

    public function getTotalprecipMm(): ?float
    {
        return $this->totalprecip_mm;
    }

    public function setTotalprecipMm(float $totalprecip_mm): self
    {
        $this->totalprecip_mm = $totalprecip_mm;

        return $this;
    }

    public function getAvgvisKm(): ?float
    {
        return $this->avgvis_km;
    }

    public function setAvgvisKm(float $avgvis_km): self
    {
        $this->avgvis_km = $avgvis_km;

        return $this;
    }

    public function getAvghumidity(): ?float
    {
        return $this->avghumidity;
    }

    public function setAvghumidity(float $avghumidity): self
    {
        $this->avghumidity = $avghumidity;

        return $this;
    }

    public function getDailyWillItRain(): ?bool
    {
        return $this->daily_will_it_rain;
    }

    public function setDailyWillItRain(bool $daily_will_it_rain): self
    {
        $this->daily_will_it_rain = $daily_will_it_rain;

        return $this;
    }

    public function getDailyChanceOfRain(): ?float
    {
        return $this->daily_chance_of_rain;
    }

    public function setDailyChanceOfRain(float $daily_chance_of_rain): self
    {
        $this->daily_chance_of_rain = $daily_chance_of_rain;

        return $this;
    }

    public function getDailyWillItSnow(): ?bool
    {
        return $this->daily_will_it_snow;
    }

    public function setDailyWillItSnow(bool $daily_will_it_snow): self
    {
        $this->daily_will_it_snow = $daily_will_it_snow;

        return $this;
    }

    public function getDailyChanceOfSnow(): ?float
    {
        return $this->daily_chance_of_snow;
    }

    public function setDailyChanceOfSnow(float $daily_chance_of_snow): self
    {
        $this->daily_chance_of_snow = $daily_chance_of_snow;

        return $this;
    }

    public function getUv(): ?float
    {
        return $this->uv;
    }

    public function setUv(float $uv): self
    {
        $this->uv = $uv;

        return $this;
    }

    public function getHours(): ?array
    {
        return $this->hours;
    }

    public function setHours(?array $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function getConditionText(): ?string
    {
        return $this->condition_text;
    }

    public function setConditionText(string $condition_text): self
    {
        $this->condition_text = $condition_text;

        return $this;
    }

    public function getConditionIcon(): ?string
    {
        return $this->condition_icon;
    }

    public function setConditionIcon(string $condition_icon): self
    {
        $this->condition_icon = $condition_icon;

        return $this;
    }
}
