<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/espace-membre", name="user_homepage")
     */
    public function index(): Response
    {
        return $this->render('user/pages/index.html.twig');
    }
}
