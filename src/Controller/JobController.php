<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use App\Event\JobCheckEvent;
use App\Event\JobStartEvent;
use App\Event\JobStopEvent;
use App\Event\JobUpdateEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function json_decode;

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

        $event = new JobStartEvent($job);
        $eventDispatcher->dispatch(JobEventEnum::JOB_START, $event);

        return $this->serializeObject($job, ['job']);
    }

    /**
     * @Route(
     *     name="check_job",
     *     path="/api/jobs/check"
     * )
     * @Method("GET")
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return Response
     */
    public function areJobsRunningAction(EventDispatcherInterface $eventDispatcher)
    {
        $jobs = $this->getRepository()->findAll();

        /** @var Job $job */
        foreach ($jobs as $job) {
            $event = new JobCheckEvent($job);

            $eventDispatcher->dispatch(JobEventEnum::JOB_CHECK, $event);
        }

        return $this->serializeObject("");
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

        $event = new JobStopEvent($job);
        $eventDispatcher->dispatch(JobEventEnum::JOB_STOP, $event);

        return $this->serializeObject($job, ['job']);
    }

    /**
     * @Route(
     *     name="set_data",
     *     path="/api/jobs/{id}",
     *     requirements={"id"="\d+"},
     * )
     * @Method("PUT")
     * @param Request                  $request
     * @param EventDispatcherInterface $eventDispatcher
     * @param                          $id
     *
     * @return Response
     */
    public function setAdditionalInformation(Request $request, EventDispatcherInterface $eventDispatcher, $id)
    {
        /** @var Job $job */
        $job = $this->getRepository()->find($id);

        $data = $request->getContent();
        $json = json_decode($data);

        $event = new JobUpdateEvent($job, $json->additionalData);
        $eventDispatcher->dispatch(JobEventEnum::JOB_UPDATE, $event);

        return $this->serializeObject($job, ['job']);
    }

}
