<?php

namespace App\Entity;
use App\Repository\ComplexeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Complexe
 *
 * @ORM\Table(name="complexe", indexes={@ORM\Index(name="fk_complexe", columns={"idlocateur"})})
 * @ORM\Entity(repositoryClass=ComplexeRepository::class)
 */

 #[ORM\Entity(repositoryClass: ComplexeRepository::class)]

class Complexe
{
    /**
     * @var int
     *
     * @ORM\Column(name="ref", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
      * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="Le nom ne peut contenir que des lettres."
     * )
     * @Assert\NotBlank(message="Le nom ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="L'adresse ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="L'adresse ne peut pas dépasser {{ limit }} caractères")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=false)
     * @Assert\Regex(
     *     pattern="/^[0-9]{8}$/",
     *     message="Le numéro de téléphone doit contenir exactement 8 chiffres."
     * )
     * @Assert\NotBlank(message="Le numéro de téléphone ne peut pas être vide")
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="patente", type="string", length=255, nullable=false)
     *   @Assert\NotBlank(message="La patente ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="La patente ne peut pas dépasser {{ limit }} caractères")
     */
    private $patente;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
      * @Assert\NotBlank(message="L'image ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="Le chemin de l'image ne peut pas dépasser {{ limit }} caractères")
     */
    private $image;

    /**
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idlocateur", referencedColumnName="pseudo")
     * })
     */
    private $idlocateur;

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPatente(): ?string
    {
        return $this->patente;
    }

    public function setPatente(string $patente): static
    {
        $this->patente = $patente;

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

    public function getIdlocateur(): ?Utilisateurs
    {
        return $this->idlocateur;
    }

    public function setIdlocateur(?Utilisateurs $idlocateur): static
    {
        $this->idlocateur = $idlocateur;

        return $this;
    }

    public function __toString()
    {
        return $this->nom; // Retourne le nom de l'utilisateur
    }
}
