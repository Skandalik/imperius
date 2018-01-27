<?php
declare(strict_types=1);
namespace App\Action;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SensorActions extends Controller
{
    /**
     * @Route(
     *     name="get_status",
     *     path="/status/{id}",
     *     defaults={
     *          "_api_item_operation_name"="status"
     *     }
     * )
     * @Method("GET")
     * @param Request $request
     *
     * @return mixed
     */
    public function getStatusAction(Request $request)
    {
        return $request;
    }

    /**
     * @Route(
     *     name="set_status",
     *     path="/status/set/{id}",
     *     defaults={
     *          "_api_item_operation_name"="set_status"
     *     }
     * )
     * @Method("PUT")
     * @param Request $request
     *
     * @return mixed
     */
    public function setStatusAction(Request $request)
    {
        return $request;
    }
}