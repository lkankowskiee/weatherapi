<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $name;

    #[ORM\Column(type: 'string', length: 50)]
    private string $country;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Forecast::class, orphanRemoval: true)]
    private $forecasts;

    public function __construct()
    {
        $this->forecasts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Forecast[]
     */
    public function getForecasts(): Collection
    {
        return $this->forecasts;
    }

    public function addForecast(Forecast $forecast): self
    {
        if (!$this->forecasts->contains($forecast)) {
            $this->forecasts[] = $forecast;
            $forecast->setLocation($this);
        }

        return $this;
    }

    public function removeForecast(Forecast $forecast): self
    {
        if ($this->forecasts->removeElement($forecast)) {
            // set the owning side to null (unless already changed)
            if ($forecast->getLocation() === $this) {
                $forecast->setLocation(null);
            }
        }

        return $this;
    }
}
