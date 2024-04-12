<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event", indexes={@ORM\Index(name="fk_user", columns={"iduser"}), @ORM\Index(name="fk_terrain", columns={"idterrain"}), @ORM\Index(name="idsponso", columns={"idsponso"})})
 * @ORM\Entity
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
     */
    private $idsponso;

    /**
     * @var \Terrain
     *
     * @ORM\ManyToOne(targetEntity="Terrain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idterrain", referencedColumnName="id")
     * })
     */
    private $idterrain;

    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getIduser(): ?Utilisateurs
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateurs $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdsponso(): ?Sponso
    {
        return $this->idsponso;
    }

    public function setIdsponso(?Sponso $idsponso): static
    {
        $this->idsponso = $idsponso;

        return $this;
    }

    public function getIdterrain(): ?Terrain
    {
        return $this->idterrain;
    }

    public function setIdterrain(?Terrain $idterrain): static
    {
        $this->idterrain = $idterrain;

        return $this;
    }


}
