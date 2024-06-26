<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="idUser", columns={"idUser"})})
<<<<<<< HEAD
 * @ORM\Entity
 */
class Commande
{
    /**
=======
 * @ORM\Entity(repositoryClass=App\Repository\CommandeRepository::class) */

class Commande
{
   /**
>>>>>>> storeWeb
     * @var int
     *
     * @ORM\Column(name="refCommande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
<<<<<<< HEAD
    private $refcommande;
=======
    private $refCommande; // Mettez à jour le nom de l'attribut
>>>>>>> storeWeb

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=0, nullable=true, options={"default"="enAttente"})
     */
    private $status = 'enAttente';

    /**
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="pseudo")
     * })
     */
    private $iduser;

<<<<<<< HEAD
    public function getRefcommande(): ?int
    {
        return $this->refcommande;
=======
    public function getRefCommande(): ?int
    {
        return $this->refCommande;
    }

    public function setRefCommande(int $refCommande): self
    {
        $this->refCommande = $refCommande;
        return $this;
>>>>>>> storeWeb
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

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
