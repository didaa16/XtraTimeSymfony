<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_reservation_terrain", columns={"terrainId"}), @ORM\Index(name="fk_reservation_utilisateur", columns={"clientPseudo"})})
 * @ORM\Entity
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="date", type="string", length=255, nullable=true)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="duree", type="string", length=255, nullable=true)
     */
    private $duree;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @var int|null
     *
     * @ORM\Column(name="terrainId", type="integer", nullable=true)
     */
    private $terrainid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="clientPseudo", type="string", length=255, nullable=true)
     */
    private $clientpseudo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="equipements", type="string", length=255, nullable=true)
     */
    private $equipements;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTerrainid(): ?int
    {
        return $this->terrainid;
    }

    public function setTerrainid(?int $terrainid): static
    {
        $this->terrainid = $terrainid;

        return $this;
    }

    public function getClientpseudo(): ?string
    {
        return $this->clientpseudo;
    }

    public function setClientpseudo(?string $clientpseudo): static
    {
        $this->clientpseudo = $clientpseudo;

        return $this;
    }

    public function getEquipements(): ?string
    {
        return $this->equipements;
    }

    public function setEquipements(?string $equipements): static
    {
        $this->equipements = $equipements;

        return $this;
    }


}
