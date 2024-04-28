<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ratingprod
 *
 * @ORM\Table(name="ratingprod", indexes={@ORM\Index(name="ref", columns={"ref"}), @ORM\Index(name="idUser", columns={"idUser"})})
 * @ORM\Entity
 */
class Ratingprod
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
     * @var float|null
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=true)
     */
    private $rating;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ref", referencedColumnName="ref")
     * })
     */
    private $ref;

    /**
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="pseudo")
     * })
     */
    private $iduser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getRef(): ?Produit
    {
        return $this->ref;
    }

    public function setRef(?Produit $ref): static
    {
        $this->ref = $ref;

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


}
