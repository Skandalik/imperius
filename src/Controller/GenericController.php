<?php
declare(strict_types=1);
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenericController extends Controller
{
    /** @var string */
    protected $entityClass = '';

    /** @var string */
    protected $formType = '';

    /** @var  string */
    protected $route;

    /** @var EntityManager */
    private $entityManager;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * GenericController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface    $serializer
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->route = $this->extractRoute();
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $message = null;

        return $this->getView(
            'index',
            [
                'message' => $message,
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $entity = $this->createEntity();

        return $this->handleFormCreation($entity, $request, 'add', 'success', 'Success! Entity created!');
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->createEntity($id);

        return $this->handleFormCreation($entity, $request, 'edit', 'success', 'Success! Entity edited!');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        return $this->getView('show');
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->getRepository()->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $this->addFlashMessage($request, 'success', 'Success! Entity deleted.');

        return $this->redirectToRoute('sensor');
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * @param         $entity
     * @param Request $request
     * @param string  $typeOfMessage
     * @param string  $message
     *
     * @return Response
     */
    private function handleFormCreation(
        $entity,
        Request $request,
        string $viewName,
        string $typeOfMessage,
        string $message
    ) {
        /** @var FormInterface $form */
        $form = $this->createForm($this->formType, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $this->addFlashMessage($request, $typeOfMessage, $message);

            return $this->redirectToRoute($this->route);
        }

        return $this->getView(
            $viewName,
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @return array
     */
    private function getEntities(): array
    {
        $repo = $this->getRepository();

        if (!is_null($repo)) {
            return $this->getRepository()->findAll();
        }

        return [];
    }

    /**
     * @param $id
     *
     * @return null|object
     */
    private function createEntity($id = null)
    {
        if (!is_null($id)) {
            return $this->getRepository()->find($id);
        }

        return new $this->entityClass();
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
     * @param string $viewName
     * @param array  $parameters
     *
     * @return Response
     */
    protected function getView(string $viewName, array $parameters = [])
    {
        $entities = $this->getEntities();

        $parameters['entities'] = $entities;
        $parameters['route'] = $this->route;

        return $this->render($this->getViewTemplate($viewName), $parameters);
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param string $templateFileName
     *
     * @return string
     */
    private function getViewTemplate(string $templateFileName): string
    {
        return sprintf('%s\%s.html.twig', $this->getControllerTemplateDirectory(), $templateFileName);
    }

    /**
     * @return string
     */
    private function getControllerTemplateDirectory(): string
    {
        return substr(get_class($this), 4, -10);
    }

    /**
     * @param Request $request
     * @param string  $typeOfMessage
     * @param string  $message
     */
    private function addFlashMessage(Request $request, string $typeOfMessage, string $message)
    {
        $request->getSession()->getFlashBag()->add($typeOfMessage, $message);
    }

    /**
     * @return string
     */
    private function extractRoute(): string
    {
        $elements = explode("\\", $this->entityClass);

        return strtolower(end($elements));
    }

    private function splitByCamelCase(string $str)
    {
        $formattedStr = '';
        $re = '/
          (?<=[a-z])
          (?=[A-Z])
        | (?<=[A-Z])
          (?=[A-Z][a-z])
        /x';
        $a = preg_split($re, $str);
        $formattedStr = implode(' ', $a);

        return $formattedStr;
    }
}