<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="duree", type="datetime", nullable=true)
     */
    private $duree;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @var string|null
     *
     * @ORM\Column(name="equipements", type="string", length=255, nullable=true)
     */
    private $equipements;

    /**
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clientPseudo", referencedColumnName="pseudo")
     * })
     */
    private $clientpseudo;

    /**
     * @var \Terrain
     *
     * @ORM\ManyToOne(targetEntity="Terrain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="terrainId", referencedColumnName="id")
     * })
     */
    private $terrainid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Equipement", inversedBy="idreservation")
     * @ORM\JoinTable(name="reservation_equipement",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idReservation", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idEquipement", referencedColumnName="idEquipement")
     *   }
     * )
     */
    private $idequipement = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idequipement = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(?\DateTimeInterface $duree): static
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

    public function getEquipements(): ?string
    {
        return $this->equipements;
    }

    public function setEquipements(?string $equipements): static
    {
        $this->equipements = $equipements;

        return $this;
    }

    public function getClientpseudo(): ?Utilisateurs
    {
        return $this->clientpseudo;
    }

    public function setClientpseudo(?Utilisateurs $clientpseudo): static
    {
        $this->clientpseudo = $clientpseudo;

        return $this;
    }

    public function getTerrainid(): ?Terrain
    {
        return $this->terrainid;
    }

    public function setTerrainid(?Terrain $terrainid): static
    {
        $this->terrainid = $terrainid;

        return $this;
    }

    /**
     * @return Collection<int, Equipement>
     */
    public function getIdequipement(): Collection
    {
        return $this->idequipement;
    }

    public function addIdequipement(Equipement $idequipement): static
    {
        if (!$this->idequipement->contains($idequipement)) {
            $this->idequipement->add($idequipement);
        }

        return $this;
    }

    public function removeIdequipement(Equipement $idequipement): static
    {
        $this->idequipement->removeElement($idequipement);

        return $this;
    }

}
