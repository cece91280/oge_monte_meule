<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    /**
     * @var Collection<int, DevisStatus>
     */
    #[ORM\OneToMany(targetEntity: DevisStatus::class, mappedBy: 'status')]
    private Collection $devisStatus;

    public function __construct()
    {
        $this->devisStatus = new ArrayCollection();
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

    /**
     * @return Collection<int, DevisStatus>
     */
    public function getDevisStatus(): Collection
    {
        return $this->devisStatus;
    }

    public function addDevisStatus(DevisStatus $devisStatus): static
    {
        if (!$this->devisStatus->contains($devisStatus)) {
            $this->devisStatus->add($devisStatus);
            $devisStatus->setStatus($this);
        }

        return $this;
    }

    public function removeDevisStatus(DevisStatus $devisStatus): static
    {
        if ($this->devisStatus->removeElement($devisStatus)) {
            // set the owning side to null (unless already changed)
            if ($devisStatus->getStatus() === $this) {
                $devisStatus->setStatus(null);
            }
        }

        return $this;
    }
}
