<?php
declare(strict_types=1);
namespace App\Util\BehaviorApplet\Factory;

use App\Util\BehaviorApplet\DataObject\BehaviorDataObject;

class BehaviorDataObjectFactory
{
    /**
     * @param array $behavior
     *
     * @return BehaviorDataObject
     */
    public function create(array $behavior): BehaviorDataObject
    {
        return new BehaviorDataObject($behavior);
    }
}