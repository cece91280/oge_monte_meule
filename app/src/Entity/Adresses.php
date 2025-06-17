<?php

namespace App\Entity;

use App\Repository\AdressesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdressesRepository::class)]
class Adresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_nom = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 5)]
    private ?string $code_postal = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $etage = null;

    #[ORM\Column]
    private ?bool $ascenseur = null;

    #[ORM\OneToOne(mappedBy: 'adresses_arriver', cascade: ['persist', 'remove'])]
    private ?Devis $devis_arriver = null;

    #[ORM\OneToOne(mappedBy: 'adresses_depart', cascade: ['persist', 'remove'])]
    private ?Devis $devis_depart = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseNom(): ?string
    {
        return $this->adresse_nom;
    }

    public function setAdresseNom(string $adresse_nom): static
    {
        $this->adresse_nom = $adresse_nom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(int $etage): static
    {
        $this->etage = $etage;

        return $this;
    }

    public function isAscenseur(): ?bool
    {
        return $this->ascenseur;
    }

    public function setAscenseur(bool $ascenseur): static
    {
        $this->ascenseur = $ascenseur;

        return $this;
    }

    public function getDevisArriver(): ?Devis
    {
        return $this->devis_arriver;
    }

    public function setDevisArriver(Devis $devis_arriver): static
    {
        // set the owning side of the relation if necessary
        if ($devis_arriver->getAdressesArriver() !== $this) {
            $devis_arriver->setAdressesArriver($this);
        }

        $this->devis_arriver = $devis_arriver;

        return $this;
    }

    public function getDevisDepart(): ?Devis
    {
        return $this->devis_depart;
    }

    public function setDevisDepart(Devis $devis_depart): static
    {
        // set the owning side of the relation if necessary
        if ($devis_depart->getAdressesDepart() !== $this) {
            $devis_depart->setAdressesDepart($this);
        }

        $this->devis_depart = $devis_depart;

        return $this;
    }


}
