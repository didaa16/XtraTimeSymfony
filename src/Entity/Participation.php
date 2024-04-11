<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="participation", indexes={@ORM\Index(name="idevent", columns={"idevent"}), @ORM\Index(name="iduser", columns={"iduser"})})
 * @ORM\Entity(repositoryClass=ParticipationRepository::class)
 */
class Participation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idparticipation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idparticipation;

    /**
     * @var Utilisateurs|null
     *
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class)
     * @ORM\JoinColumn(name="iduser", referencedColumnName="pseudo")
     */
    private $iduser;

    /**
     * @var Event|null
     *
     * @ORM\ManyToOne(targetEntity=Event::class)
     * @ORM\JoinColumn(name="idevent", referencedColumnName="idevent")
     */
    private $idevent;

    public function getIdparticipation(): ?int
    {
        return $this->idparticipation;
    }

    public function getIduser(): ?Utilisateurs
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateurs $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdevent(): ?Event
    {
        return $this->idevent;
    }

    public function setIdevent(?Event $idevent): self
    {
        $this->idevent = $idevent;

        return $this;
    }
}
