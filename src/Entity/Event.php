<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
<<<<<<< HEAD
 * @ORM\Table(name="event", indexes={@ORM\Index(name="fk_terrain", columns={"idterrain"}), @ORM\Index(name="idsponso", columns={"idsponso"}), @ORM\Index(name="fk_user", columns={"iduser"})})
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
=======
 * @ORM\Table(name="event", indexes={@ORM\Index(name="fk_user", columns={"iduser"}), @ORM\Index(name="fk_terrain", columns={"idterrain"}), @ORM\Index(name="idsponso", columns={"idsponso"})})
 * @ORM\Entity
>>>>>>> storeWeb
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="idevent", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevent;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datedebut", type="datetime", nullable=true)
     */
    private $datedebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datefin", type="datetime", nullable=true)
     */
    private $datefin;

    /**
<<<<<<< HEAD
     * @var Sponso|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Sponso")
     * @ORM\JoinColumn(name="idsponso", referencedColumnName="idsponso")
=======
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="pseudo")
     * })
     */
    private $iduser;

    /**
     * @var \Sponso
     *
     * @ORM\ManyToOne(targetEntity="Sponso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idsponso", referencedColumnName="idsponso")
     * })
>>>>>>> storeWeb
     */
    private $idsponso;

    /**
<<<<<<< HEAD
     * @var Terrain|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Terrain")
     * @ORM\JoinColumn(name="idterrain", referencedColumnName="id")
     */
    private $idterrain;

    /**
     * @var Utilisateurs|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateurs")
     * @ORM\JoinColumn(name="iduser", referencedColumnName="pseudo")
     */
    private $iduser;

=======
     * @var \Terrain
     *
     * @ORM\ManyToOne(targetEntity="Terrain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idterrain", referencedColumnName="id")
     * })
     */
    private $idterrain;

>>>>>>> storeWeb
    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

<<<<<<< HEAD
    public function setTitre(string $titre): self
=======
    public function setTitre(string $titre): static
>>>>>>> storeWeb
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

<<<<<<< HEAD
    public function setDescription(string $description): self
=======
    public function setDescription(string $description): static
>>>>>>> storeWeb
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

<<<<<<< HEAD
    public function setImage(string $image): self
=======
    public function setImage(string $image): static
>>>>>>> storeWeb
    {
        $this->image = $image;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

<<<<<<< HEAD
    public function setDatedebut(?\DateTimeInterface $datedebut): self
=======
    public function setDatedebut(?\DateTimeInterface $datedebut): static
>>>>>>> storeWeb
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

<<<<<<< HEAD
    public function setDatefin(?\DateTimeInterface $datefin): self
=======
    public function setDatefin(?\DateTimeInterface $datefin): static
>>>>>>> storeWeb
    {
        $this->datefin = $datefin;

        return $this;
    }

<<<<<<< HEAD
=======
    public function getIduser(): ?Utilisateurs
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateurs $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

>>>>>>> storeWeb
    public function getIdsponso(): ?Sponso
    {
        return $this->idsponso;
    }

<<<<<<< HEAD
    public function setIdsponso(?Sponso $idsponso): self
=======
    public function setIdsponso(?Sponso $idsponso): static
>>>>>>> storeWeb
    {
        $this->idsponso = $idsponso;

        return $this;
    }

    public function getIdterrain(): ?Terrain
    {
        return $this->idterrain;
    }

<<<<<<< HEAD
    public function setIdterrain(?Terrain $idterrain): self
=======
    public function setIdterrain(?Terrain $idterrain): static
>>>>>>> storeWeb
    {
        $this->idterrain = $idterrain;

        return $this;
    }

<<<<<<< HEAD
    public function getIduser(): ?Utilisateurs
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateurs $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }
    


}
=======

}
>>>>>>> storeWeb
