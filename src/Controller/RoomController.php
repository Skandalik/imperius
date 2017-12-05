<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;

class RoomController extends GenericController
{
    protected $entityClass = Room::class;

    protected $formType = RoomType::class;
}