<?php

namespace App\Entity;

use App\Repository\PrestationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationsRepository::class)]
class Prestations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix_de_base = null;

    /**
     * @var Collection<int, DevisPrestations>
     */
    #[ORM\OneToMany(targetEntity: DevisPrestations::class, mappedBy: 'prestations')]
    private Collection $devisPrestations;

    public function __construct()
    {
        $this->devisPrestations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixDeBase(): ?float
    {
        return $this->prix_de_base;
    }

    public function setPrixDeBase(float $prix_de_base): static
    {
        $this->prix_de_base = $prix_de_base;

        return $this;
    }

    /**
     * @return Collection<int, DevisPrestations>
     */
    public function getDevisPrestations(): Collection
    {
        return $this->devisPrestations;
    }

    public function addDevisPrestation(DevisPrestations $devisPrestation): static
    {
        if (!$this->devisPrestations->contains($devisPrestation)) {
            $this->devisPrestations->add($devisPrestation);
            $devisPrestation->setPrestations($this);
        }

        return $this;
    }

    public function removeDevisPrestation(DevisPrestations $devisPrestation): static
    {
        if ($this->devisPrestations->removeElement($devisPrestation)) {
            // set the owning side to null (unless already changed)
            if ($devisPrestation->getPrestations() === $this) {
                $devisPrestation->setPrestations(null);
            }
        }

        return $this;
    }


}
