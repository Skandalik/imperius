<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Sensor;
use App\Type\SensorStateEnumType;
use App\Util\SensorManager\SensorMosquittoPublisher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use function intval;
use function strval;

class SensorController extends GenericController
{
    protected $entityClass = Sensor::class;

    /**
     * @Route(
     *     name="set_status",
     *     path="/api/sensors/{id}/status/set/{status}",
     *     defaults={
     *          "_api_item_operation_name"="set_status"
     *     }
     * )
     * @Method("PUT")
     * @param SensorMosquittoPublisher $publisher
     * @param                          $id
     * @param                          $status
     *
     * @return Response
     */
    public function setStatusAction(SensorMosquittoPublisher $publisher, $id, $status)
    {
        /** @var Sensor $sensor */
        $sensor = $this->getRepository()->find($id);
        $publisher->publishSetSensorStatus($sensor, $status);

        $sensor->setStatus(intval($status));

        return $this->serializeObject($sensor, ['sensor']);
    }

    /**
     * @Route(
     *     name="set_state",
     *     path="/api/sensors/{id}/set/{status}",
     *     defaults={
     *          "_api_item_operation_name"="set_state"
     *     }
     * )
     * @Method("PUT")
     * @param SensorMosquittoPublisher $publisher
     * @param                          $id
     * @param                          $status
     *
     * @return Response
     */
    public function setStateAction(SensorMosquittoPublisher $publisher, $id, $status)
    {
        /** @var Sensor $sensor */
        $sensor = $this->getRepository()->find($id);
        $publisher->publishSetSensorStatus($sensor, strval(SensorStateEnumType::getFlippedValue($status)));

        return $this->serializeObject($sensor, ["sensor"]);
    }

    /**
     * @Route(
     *     name="check_status",
     *     path="/api/sensors/{id}/status/check",
     *     requirements={"id"="\d+"},
     *     defaults={
     *          "_api_item_operation_name"="check_status"
     *     }
     * )
     * @Method("GET")
     * @param SensorMosquittoPublisher $publisher
     * @param                          $id
     *
     * @return Response
     */
    public function checkStatusAction(SensorMosquittoPublisher $publisher, $id)
    {
        /** @var Sensor $sensor */
        $sensor = $this->getRepository()->find($id);
        $publisher->publishCheckSensorStatus($sensor);

        return $this->serializeObject($sensor, ["sensor"]);
    }

    /**
     * @Route(
     *     name="check_bulk_status",
     *     path="/api/sensors/all/status/check",
     *     defaults={
     *          "_api_item_operation_name"="check_bulk_status"
     *     }
     * )
     * @Method("GET")
     * @param SensorMosquittoPublisher $publisher
     *
     * @return Response
     */
    public function checkBulkStatusAction(SensorMosquittoPublisher $publisher)
    {
        $publisher->publishCheckAllSensorsStatus();

        return $this->serializeObject($this->getRepository()->findAll(), ["sensor"]);
    }
}
