<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;



/**
 * Produit
 *
 * @ORM\Table(name="produit")
   * @ORM\Entity(repositoryClass=App\Repository\ProduitRepository::class) */

class Produit
{
   /**
 * @var string
 *
 * @ORM\Column(name="ref", type="string", length=255, nullable=false)
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="NONE")
 * @Assert\NotBlank(message="La référence ne peut pas être vide.")
 * @Assert\Regex(
 *     pattern="/^RF-\d+$/",
 *     message="La référence doit être au format RF-suivi d'un nombre."
 * )
 */
private $ref;

   /**
 * @var string|null
 *
 * @ORM\Column(name="nom", type="string", length=20, nullable=true)
 *  @Assert\NotBlank(message="Le nom ne peut pas être vide.")

 * @Assert\Length(
 *     max=20,
 *     maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères."
 * )
 * @Assert\NotBlank(message="Le nom ne peut pas être vide.")
 */
private $nom;

 /**
 * @var string|null
 *
 * @ORM\Column(name="description", type="string", length=200, nullable=true)
 *  @Assert\NotBlank(message="La description ne peut pas être vide.")
 * @Assert\Length(
 *     min=20,
 *     minMessage="La description doit contenir au moins {{ limit }} caractères.",
 *     max=200,
 *     maxMessage="La description ne doit pas dépasser {{ limit }} caractères."
 * )
 * @Assert\NotBlank(message="La description ne peut pas être vide.")
 */
private $description;

    /**
 * @var float|null
 *
 * @ORM\Column(name="prix", type="float", nullable=true)
*  @Assert\NotBlank(message="Le prix ne peut pas être vide.")
 * @Assert\GreaterThan(
 *     value=0,
 *     message="Le prix doit être supérieur à zéro."
 * )
 */
private $prix;

    /**
 * @var string|null
 *
 * @ORM\Column(name="marque", type="string", length=255, nullable=true)
 * @Assert\NotBlank(message="La marque ne peut pas être vide.")
 * @Assert\Choice(
 *     choices={"nike", "adidas", "underArmour", "puma"},
 *     message="Veuillez choisir une marque valide parmi les options fournies."
 * )
 */
private $marque;

/**
 * @var string|null
 *
 * @ORM\Column(name="taille", type="string", length=255, nullable=true)
  * @Assert\NotBlank(message="La taille ne peut pas être vide.")

 * @Assert\Type(
 *     type="string",
 *     message="La taille doit être une chaîne de caractères."
 * )
 */
private $taille;


/**
 * @var string|null
 *
 * @ORM\Column(name="typeSport", type="string", length=255, nullable=true)
 * @Assert\NotBlank(message="Le type de sport ne peut pas être vide.")
 * @Assert\Choice(
 *     choices={"handball", "football", "basketball", "volleyball", "tennis"},
 *     message="Veuillez choisir un type de sport valide parmi les options fournies.",
 * )
 */
private $typesport;



  /**
 * @var int|null
 *
 * @ORM\Column(name="quantite", type="integer", nullable=true)
 *  * @Assert\NotBlank(message="La quantité ne peut pas être vide.")

 * @Assert\GreaterThanOrEqual(
 *     value=0,
 *     message="La quantité doit être supérieure ou égale à zéro."
 * )
 */
private $quantite;


 /**
 * @var string|null
 *
 * @ORM\Column(name="image", type="string", length=255, nullable=true)

 */
private $image;

    public function getRef(): ?string
    {
        return $this->ref;
    }
    public function setRef(?string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getTypesport(): ?string
    {
        return $this->typesport;
    }

    public function setTypesport(?string $typesport): static
    {
        $this->typesport = $typesport;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }


}
