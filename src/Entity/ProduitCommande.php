<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitCommande
 *
 * @ORM\Table(name="produit_commande", indexes={@ORM\Index(name="refCommande", columns={"refCommande"}), @ORM\Index(name="ref", columns={"ref"})})
<<<<<<< HEAD
 * @ORM\Entity
 */
=======
   * @ORM\Entity(repositoryClass=App\Repository\ProduitCommandeRepository::class) */

>>>>>>> storeWeb
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
<<<<<<< HEAD
    private $refcommande;
=======
    private $refCommande;
>>>>>>> storeWeb

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

<<<<<<< HEAD
    public function getRefcommande(): ?int
    {
        return $this->refcommande;
    }

    public function setRefcommande(int $refcommande): static
    {
        $this->refcommande = $refcommande;
=======
    public function getrefCommande(): ?int
    {
        return $this->refCommande;
    }

    public function setrefCommande(int $refcommande): static
    {
        $this->refCommande = $refcommande;
>>>>>>> storeWeb

        return $this;
    }


<<<<<<< HEAD
=======
    
>>>>>>> storeWeb
}
