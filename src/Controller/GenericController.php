<?php
declare(strict_types=1);
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GenericController extends Controller
{
    /** @var string */
    protected $entityClass = '';

    /** @var EntityManager */
    private $entityManager;

    /** @var SerializerInterface */
    private $serializer;

    /** @var NormalizerInterface */
    private $normalizer;

    /**
     * GenericController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface    $serializer
     * @param NormalizerInterface    $normalizer
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        NormalizerInterface $normalizer
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * @return NormalizerInterface
     */
    public function getNormalizer(): NormalizerInterface
    {
        return $this->normalizer;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return EntityRepository|null
     */
    protected function getRepository()
    {
        if (empty($this->entityClass)) {
            return null;
        }

        return $this->entityManager->getRepository($this->entityClass);
    }

    /**
     * @param mixed $user
     *
     * @param array $groups
     *
     * @return Response
     */
    protected function serializeObject($user, $groups = []): Response
    {
        $normalized = $this->getNormalizer()->normalize($user, null, ['groups' => $groups]);
        $response = new Response($this->getSerializer()->serialize($normalized, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}