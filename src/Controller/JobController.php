<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use App\Event\JobStartEvent;
use App\Event\JobStopEvent;
use function explode;
use function intval;
use function is_null;
use const PHP_EOL;
use function pi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

class JobController extends GenericController
{
    protected $entityClass = Job::class;

    /**
     * @Route(
     *     name="run_job",
     *     path="/api/jobs/{id}/run",
     *     requirements={"id"="\d+"},
     *     defaults={
     *          "_api_item_operation_name"="run_job"
     *     }
     * )
     * @Method("GET")
     * @param EventDispatcherInterface $eventDispatcher
     * @param                          $id
     *
     * @return Response
     */
    public function runJobAction(EventDispatcherInterface $eventDispatcher, $id)
    {
        /** @var Job $job */
        $job = $this->getRepository()->find($id);

        $process = new Process('php ../bin/console ' . $job->getCommand() . ' > /dev/null 2>&1 & echo $!');

        if($this->processStatus($job->getJobPid())) {
            $this->killProcess($job->getJobPid());
        }

        $process->run();

        $event = new JobStartEvent($job, intval($process->getOutput()));
        $eventDispatcher->dispatch(JobEventEnum::JOB_START, $event);

        return $this->serializeObject($job);
    }

    /**
     * @Route(
     *     name="stop_job",
     *     path="/api/jobs/{id}/stop",
     *     requirements={"id"="\d+"},
     *     defaults={
     *          "_api_item_operation_name"="stop_job"
     *     }
     * )
     * @Method("GET")
     * @param EventDispatcherInterface $eventDispatcher
     * @param                          $id
     *
     * @return Response
     */
    public function stopJobAction(EventDispatcherInterface $eventDispatcher, $id)
    {
        /** @var Job $job */
        $job = $this->getRepository()->find($id);

        $this->killProcess($job->getJobPid());

        $event = new JobStopEvent($job, null);
        $eventDispatcher->dispatch(JobEventEnum::JOB_STOP, $event);

        return $this->serializeObject($job);
    }

    /**
     * @param int $pid
     *
     * @return bool
     */
    private function killProcess(int $pid)
    {
        $process = new Process('kill ' . $pid);
        $process->run();

        return !$this->processStatus($pid);
    }

    /**
     * @param $pid
     *
     * @return bool
     */
    private function processStatus($pid)
    {
        if (is_null($pid)) {
            return false;
        }

        $process = new Process('ps -o time -p ' . $pid);
        $process->run();

        $output = explode(PHP_EOL, trim($process->getOutput()));

        return isset($output[1]);
    }

    /**
     * @param mixed $sensor
     *
     * @return Response
     */
    private function serializeObject($sensor): Response
    {
        $response = new Response($this->getSerializer()->serialize($sensor, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
