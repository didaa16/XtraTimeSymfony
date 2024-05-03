<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitCommande
 *
 * @ORM\Table(name="produit_commande", indexes={@ORM\Index(name="refCommande", columns={"refCommande"}), @ORM\Index(name="ref", columns={"ref"})})
 * @ORM\Entity
 */
class ProduitCommande
{
    /**
     * @var int
     *
     * @ORM\Column(name="nbr", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nbr;

    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=255, nullable=false)
     */
    private $ref;

    /**
     * @var int
     *
     * @ORM\Column(name="refCommande", type="integer", nullable=false)
     */
    private $refcommande;

    public function getNbr(): ?int
    {
        return $this->nbr;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getRefcommande(): ?int
    {
        return $this->refcommande;
    }

    public function setRefcommande(int $refcommande): static
    {
        $this->refcommande = $refcommande;

        return $this;
    }


}
