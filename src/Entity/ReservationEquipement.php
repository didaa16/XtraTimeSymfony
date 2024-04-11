<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReservationEquipement
 *
 * @ORM\Table(name="reservation_equipement", indexes={@ORM\Index(name="fk_equipement1", columns={"idEquipement"})})
 * @ORM\Entity
 */
class ReservationEquipement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idReservation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idreservation;

    /**
     * @var int
     *
     * @ORM\Column(name="idEquipement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idequipement;

    public function getIdreservation(): ?int
    {
        return $this->idreservation;
    }

    public function getIdequipement(): ?int
    {
        return $this->idequipement;
    }


}
