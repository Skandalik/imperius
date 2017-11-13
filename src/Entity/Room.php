<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Traits\IdentityAutoTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 * @ORM\Table(name="imp_room")
 */
class Room
{
    use IdentityAutoTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="room", type="string", nullable=false)
     */
    private $room;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Sensor", mappedBy="room")
     */
    private $sensorsInRoom;

    /**
     * @var int
     *
     * @ORM\Column(name="floor", type="integer", nullable=false)
     */
    private $floor;

    /**
     * @return string
     */
    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * @param string $room
     *
     * @return Room
     */
    public function setRoom(string $room): Room
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSensorsInRoom(): Collection
    {
        return $this->sensorsInRoom;
    }

    /**
     * @param Collection $sensorsInRoom
     *
     * @return Room
     */
    public function setSensorsInRoom(Collection $sensorsInRoom): Room
    {
        $this->sensorsInRoom = $sensorsInRoom;

        return $this;
    }

    /**
     * @return int
     */
    public function getFloor(): int
    {
        return $this->floor;
    }

    /**
     * @param int $floor
     *
     * @return Room
     */
    public function setFloor(int $floor): Room
    {
        $this->floor = $floor;

        return $this;
    }


}