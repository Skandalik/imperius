<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentityAutoTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={"normalization_context"={"groups"={"room"}}})
 * @ORM\HasLifecycleCallbacks
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
     * @Groups({"room", "sensor"})
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
     * @Groups({"room", "sensor"})
     */
    private $floor;

    /**
     * Room constructor.
     */
    public function __construct()
    {
        $this->room = $this->createRoom();
        $this->floor = $this->createFloor();
        $this->sensorsInRoom = new ArrayCollection();
    }


    // Adding both an adder and a remover as well as updating the reverse relation are mandatory
    // if you want Doctrine to automatically update and persist (thanks to the "cascade" option) the related entity
    public function addSensor(Sensor $sensor): void
    {
        $sensor->setRoom($this);
        $this->sensorsInRoom->add($sensor);
    }

    public function removeSensor(Sensor $sensor): void
    {
        $sensor->setRoom(null);
        $this->sensorsInRoom->removeElement($sensor);
    }


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

    /**
     * @return string
     */
    private function createRoom(): string
    {
        return "";
    }

    /**
     * @return int
     */
    private function createFloor(): int
    {
        return 0;
    }

}