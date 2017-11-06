<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @\Symfony\Component\Routing\Annotation\Route("/")
     */
    public function indexAction()
    {
        return $this->render('base.html.twig');
    }
}