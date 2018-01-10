<?php
declare(strict_types=1);
namespace App\Entity\Traits;

use Symfony\Component\Serializer\Annotation\Groups;

trait IdentityAutoTrait
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"room", "sensor", "behavior", "schedule"})
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}