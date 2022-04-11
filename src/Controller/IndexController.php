<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('index.html.twig', []);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return ['twig'];
    }
}