<?php
declare(strict_types=1);
namespace App\Util\LogHelper;

use App\Type\Abstraction\AbstractEnumType;

class LogContextEnum extends AbstractEnumType
{
    const SENSOR_UUID = 'sensor_uuid';
    const SENSOR_ID = 'sensor_id';
    const SENSOR_IP = 'sensor_ip';

    const ACTION_SENSOR_UUID = 'action_sensor_uuid';
    const ACTION_SENSOR_ID = 'action_sensor_id';
    const ACTION_SENSOR_IP = 'action_sensor_ip';

    const JOB_ID = 'job_id';
    const JOB_NAME = 'job_name';
    const JOB_COMMAND = 'job_command';
    const JOB_PID = 'job_pid';

    const SCHEDULED_BEHAVIOR_ID = 'scheduled_behavior_id';
    const MANUAL_BEHAVIOR_ID = 'manual_behavior_id';
}