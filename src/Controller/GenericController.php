<?php
declare(strict_types=1);
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GenericController extends Controller
{
    /** @var string */
    protected $entityClass = '';

    /** @var string */
    protected $formType = '';

    /** @var  string */
    protected $route;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->route = $this->extractRoute();
    }

    public function indexAction(Request $request)
    {
        $message = null;
        if ($request->query->has('message')) {
            $message = $request->query->get('message');
        }

        return $this->getView(
            'index',
            [
                'message' => $message,
            ]
        );
    }

    public function addAction(Request $request)
    {
        $entity = $this->createEntity();

        return $this->handleFormCreation($entity, $request, 'success', 'Success! Entity created!');
    }

    public function editAction(Request $request, $id)
    {
        $entity = $this->createEntity($id);

        return $this->handleFormCreation($entity, $request, 'success', 'Success! Entity edited!');

    }

    public function showAction(Request $request)
    {
        return $this->getView('show');
    }

    public function deleteAction(Request $request, $id)
    {
        $entity = $this->getRepository()->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $this->addFlashMessage($request, 'success', 'Success! Entity deleted.');

        return $this->redirectToRoute('sensor');
    }

    private function handleFormCreation($entity, Request $request, string $typeOfMessage, string $message)
    {
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
            'add',
            [
                'form' => $form->createView(),
            ]
        );
    }

    private function getEntities()
    {
        $repo = $this->getRepository();

        if (!is_null($repo)) {
            return $this->getRepository()->findAll();
        }

        return [];
    }

    private function createEntity($id = null)
    {
        if (!is_null($id)) {
            return $this->getRepository()->find($id);
        }
        return new $this->entityClass();
    }

    private function getRepository()
    {
        if (empty($this->entityClass)) {
            return null;
        }

        return $this->entityManager->getRepository($this->entityClass);
    }

    protected function getView(string $viewName, array $parameters = [])
    {
        $entities = $this->getEntities();

        $parameters['entities'] = $entities;
        $parameters['route'] = $this->route;

        return $this->render($this->getViewTemplate($viewName), $parameters);
    }

    private function getViewTemplate(string $templateFileName)
    {
        return sprintf('%s\%s.html.twig', $this->getControllerTemplateDirectory(), $templateFileName);
    }

    private function getControllerTemplateDirectory()
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