<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Job;
use App\Entity\User;
use App\Event\Enum\JobEventEnum;
use App\Event\JobStartEvent;
use App\Event\JobStopEvent;
use function explode;
use function intval;
use function is_null;
use function json_decode;
use const PHP_EOL;
use function pi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends GenericController
{
    protected $entityClass = User::class;

    /**
     * @Route(
     *     name="change_password",
     *     path="/api/profile",
     *     defaults={
     *          "_api_item_operation_name"="change_password"
     *     }
     * )
     * @Method("PUT")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function changePasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userData = json_decode($request->getContent());
        /** @var User $user */
        $user = $this->getRepository()->find($userData->id);

        $user->setPassword($passwordEncoder->encodePassword($user, $userData->password));

        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
        return new Response('Password changed!');
    }

    /**
     * @Route(
     *     name="get_profile",
     *     path="/api/profile",
     *     defaults={
     *          "_api_item_operation_name"="get_profile"
     *     }
     * )
     * @Method("GET")
     *
     * @return Response
     */
    public function getProfileAction()
    {
        /** @var User $user */
        $user = $this->getRepository()->find(1);
        return $this->serializeObject($user);
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
