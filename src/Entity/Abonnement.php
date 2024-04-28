<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement", indexes={@ORM\Index(name="fk_abonnement_terrian", columns={"terrainId"}), @ORM\Index(name="abonnement_ibfk_1", columns={"packId"})})
 * @ORM\Entity(repositoryClass=App\Repository\AbonnementRepository::class)
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
     * @var \Terrain
     *
     * @ORM\ManyToOne(targetEntity="Terrain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="terrainId", referencedColumnName="id")
     * })
     */
    private $terrainid;

    /**
     * @var \Pack
     *
     * @ORM\ManyToOne(targetEntity="Pack")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="packId", referencedColumnName="idP")
     * })
     */
    private $packid;

       /**
     * @var \App\Entity\Terrain
     *
     * @ORM\ManyToOne(targetEntity=Terrain::class)
     * @ORM\JoinColumn(name="terrainId", referencedColumnName="id")
     */
    private $terrain;

    /**
     * @var \App\Entity\Pack
     *
     * @ORM\ManyToOne(targetEntity=Pack::class)
     * @ORM\JoinColumn(name="packId", referencedColumnName="idP")
     */
    private $pack;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;

        return $this;
    }

    public function getTerrain(): ?Terrain
    {
        return $this->terrain;
    }

    public function setTerrain(?Terrain $terrain): self
    {
        $this->terrain = $terrain;

        return $this;
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

    public function getTerrainid(): ?Terrain
    {
        return $this->terrainid;
    }

    public function setTerrainid(?Terrain $terrainid): static
    {
        $this->terrainid = $terrainid;

        return $this;
    }

    public function getPackid(): ?Pack
    {
        return $this->packid;
    }

    public function setPackid(?Pack $packid): static
    {
        $this->packid = $packid;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
{
    // Récupérer la date de début de l'abonnement
    $dateDebut = $this->getDate();

    // Récupérer la durée du pack associé à l'abonnement
    $dureePack = $this->getPack()->getDuree();

    // Calculer la date de fin en ajoutant la durée du pack en mois à la date de début
    $dateFin = clone $dateDebut;
    $dateFin->modify("+$dureePack months");

    return $dateFin;
}

  
}
