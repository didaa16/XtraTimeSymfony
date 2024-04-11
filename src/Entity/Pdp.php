<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pdp
 *
 * @ORM\Table(name="pdp")
 * @ORM\Entity
 */
class Pdp
{
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var \Utilisateurs
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pseudoU", referencedColumnName="pseudo")
     * })
     */
    private $pseudou;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPseudou(): ?Utilisateurs
    {
        return $this->pseudou;
    }

    public function setPseudou(?Utilisateurs $pseudou): static
    {
        $this->pseudou = $pseudou;

        return $this;
    }


}
