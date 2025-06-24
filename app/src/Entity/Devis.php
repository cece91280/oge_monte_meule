<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $prix_estime = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_demenagement = null;

    /**
     * @var Collection<int, DevisPrestations>
     */
    #[ORM\OneToMany(targetEntity: DevisPrestations::class, mappedBy: 'devis')]
    private Collection $devisPrestations;


    /**
     * @var Collection<int, DevisStatus>
     */
    #[ORM\OneToMany(targetEntity: DevisStatus::class, mappedBy: 'devis')]
    private Collection $devisStatus;

    #[ORM\OneToOne(inversedBy: 'devis_arriver', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresses $adresses_arriver = null;

    #[ORM\OneToOne(inversedBy: 'devis_depart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresses $adresses_depart = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\Column(nullable: true)]
    private ?float $volume = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;



    public function __construct()
    {
        $this->devisPrestations = new ArrayCollection();
        $this->devisStatus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixEstime(): ?float
    {
        return $this->prix_estime;
    }

    public function setPrixEstime(float $prix_estime): static
    {
        $this->prix_estime = $prix_estime;

        return $this;
    }

    public function getDateDemenagement(): ?\DateTimeImmutable
    {
        return $this->date_demenagement;
    }

    public function setDateDemenagement(\DateTimeImmutable $date_demenagement): static
    {
        $this->date_demenagement = $date_demenagement;

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
            $devisPrestation->setDevis($this);
        }

        return $this;
    }

    public function removeDevisPrestation(DevisPrestations $devisPrestation): static
    {
        if ($this->devisPrestations->removeElement($devisPrestation)) {
            // set the owning side to null (unless already changed)
            if ($devisPrestation->getDevis() === $this) {
                $devisPrestation->setDevis(null);
            }
        }

        return $this;
    }

    public function getDateArriver(): ?Adresses
    {
        return $this->date_arriver;
    }

    public function setDateArriver(Adresses $date_arriver): static
    {
        $this->date_arriver = $date_arriver;

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
            $devisStatus->setDevis($this);
        }

        return $this;
    }

    public function removeDevisStatus(DevisStatus $devisStatus): static
    {
        if ($this->devisStatus->removeElement($devisStatus)) {
            // set the owning side to null (unless already changed)
            if ($devisStatus->getDevis() === $this) {
                $devisStatus->setDevis(null);
            }
        }

        return $this;
    }

    public function getAdressesArriver(): ?Adresses
    {
        return $this->adresses_arriver;
    }

    public function setAdressesArriver(Adresses $adresses_arriver): static
    {
        $this->adresses_arriver = $adresses_arriver;

        return $this;
    }

    public function getAdressesDepart(): ?Adresses
    {
        return $this->adresses_depart;
    }

    public function setAdressesDepart(Adresses $adresses_depart): static
    {
        $this->adresses_depart = $adresses_depart;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }


}
