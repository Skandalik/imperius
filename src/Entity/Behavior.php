<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Traits\IdentityAutoTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 * @ORM\Table(name="imp_behavior")
 */
class Behavior
{
    use IdentityAutoTrait;

    /**
     * @var Sensor
     */
    private $sourceSensor;
}