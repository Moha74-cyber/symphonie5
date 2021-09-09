<?php

namespace App\Entity;

use App\Repository\RealisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RealisateurRepository::class)
 */
class Realisateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $datenaissance;

    /**
     * @ORM\OneToMany(targetEntity=Film::class, mappedBy="realisateur")
     */
    private $acteurs;

    public function __construct()
    {
        $this->acteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    /**
     * @return Collection|Film[]
     */
    public function getActeurs(): Collection
    {
        return $this->acteurs;
    }

    public function addActeur(Film $acteur): self
    {
        if (!$this->acteurs->contains($acteur)) {
            $this->acteurs[] = $acteur;
            $acteur->setRealisateur($this);
        }

        return $this;
    }

    public function removeActeur(Film $acteur): self
    {
        if ($this->acteurs->removeElement($acteur)) {
            // set the owning side to null (unless already changed)
            if ($acteur->getRealisateur() === $this) {
                $acteur->setRealisateur(null);
            }
        }

        return $this;
    }

      public function __toString()
    {
        return $this->getPrenom(). ' '.$this->getNom();
    }
}
