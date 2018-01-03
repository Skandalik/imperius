<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Sensor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SensorStatus
{
    ///**
    // * @Route(
    // *     name="get_status",
    // *     path="/status/{id}",
    // *     defaults={
    // *          "_api_item_operation_name"="status"
    // *     }
    // * )
    // * @Method("GET")
    // * @param $id
    // *
    // * @return mixed
    // */
    //public function __invoke($id)
    //{
    //    echo "lol";
    //
    //    return $id;
    //}
}