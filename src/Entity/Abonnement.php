<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Abonnement
 *
<<<<<<< HEAD
 * @ORM\Table(name="abonnement", indexes={@ORM\Index(name="fk_abonnement_terrian", columns={"terrainId"}), @ORM\Index(name="abonnement_ibfk_1", columns={"packId"})})
=======
 * @ORM\Table(name="abonnement", indexes={@ORM\Index(name="abonnement_ibfk_1", columns={"packId"}), @ORM\Index(name="fk_abonnement_terrian", columns={"terrainId"})})
>>>>>>> storeWeb
 * @ORM\Entity
 */
class Abonnement
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
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="nomPack", type="string", length=255, nullable=false)
     */
    private $nompack;

    /**
     * @var string
     *
     * @ORM\Column(name="nomterrain", type="string", length=255, nullable=false)
     */
    private $nomterrain;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomUser", type="string", length=255, nullable=true)
     */
    private $nomuser;

    /**
     * @var int
     *
     * @ORM\Column(name="numtel", type="integer", nullable=false)
     */
    private $numtel;

    /**
     * @var int
     *
     * @ORM\Column(name="prixTotal", type="integer", nullable=false)
     */
    private $prixtotal;

    /**
<<<<<<< HEAD
     * @var \Pack
     *
     * @ORM\ManyToOne(targetEntity="Pack")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="packId", referencedColumnName="idP")
     * })
     */
    private $packid;

    /**
=======
>>>>>>> storeWeb
     * @var \Terrain
     *
     * @ORM\ManyToOne(targetEntity="Terrain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="terrainId", referencedColumnName="id")
     * })
     */
    private $terrainid;

<<<<<<< HEAD
=======
    /**
     * @var \Pack
     *
     * @ORM\ManyToOne(targetEntity="Pack")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="packId", referencedColumnName="idP")
     * })
     */
    private $packid;

>>>>>>> storeWeb
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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNompack(): ?string
    {
        return $this->nompack;
    }

    public function setNompack(string $nompack): static
    {
        $this->nompack = $nompack;

        return $this;
    }

    public function getNomterrain(): ?string
    {
        return $this->nomterrain;
    }

    public function setNomterrain(string $nomterrain): static
    {
        $this->nomterrain = $nomterrain;

        return $this;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(?string $nomuser): static
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getPrixtotal(): ?int
    {
        return $this->prixtotal;
    }

    public function setPrixtotal(int $prixtotal): static
    {
        $this->prixtotal = $prixtotal;

        return $this;
    }

<<<<<<< HEAD
    public function getPackid(): ?Pack
    {
        return $this->packid;
    }

    public function setPackid(?Pack $packid): static
    {
        $this->packid = $packid;

        return $this;
    }

=======
>>>>>>> storeWeb
    public function getTerrainid(): ?Terrain
    {
        return $this->terrainid;
    }

    public function setTerrainid(?Terrain $terrainid): static
    {
        $this->terrainid = $terrainid;

        return $this;
    }

<<<<<<< HEAD
=======
    public function getPackid(): ?Pack
    {
        return $this->packid;
    }

    public function setPackid(?Pack $packid): static
    {
        $this->packid = $packid;

        return $this;
    }

>>>>>>> storeWeb

}
