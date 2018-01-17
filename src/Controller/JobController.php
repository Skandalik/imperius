<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use App\Event\JobStartEvent;
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

        $process = new Process('php ../bin/console ' . $job->getCommand() . ' > /dev/null 2>&1 &');

        if ($process->isRunning()) {
            $process->stop();
        }

        $process->disableOutput();
        $process->run();

        $event = new JobStartEvent($job, $process->getPid());
        $eventDispatcher->dispatch(JobEventEnum::JOB_START, $event);

        return $this->serializeObject($job);
    }

    /**
     * @param mixed $sensor
     *
     * @return Response
     */
    private
    function serializeObject(
        $sensor
    ): Response {
        $response = new Response($this->getSerializer()->serialize($sensor, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
