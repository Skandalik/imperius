<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function json_decode;

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
     * )
     * @Method("GET")
     *
     * @return Response
     */
    public function getProfileAction()
    {
        /** @var User $user */
        $user = $this->getRepository()->findAll()[0];

        return $this->serializeObject($user, ['user']);
    }
}
