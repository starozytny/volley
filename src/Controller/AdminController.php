<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('admin/pages/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/styleguide", name="styleguide")
     */
    public function styleguide(): Response
    {
        return $this->render('admin/pages/styleguide/index.html.twig');
    }

    /**
     * @Route("/utilisateurs", name="users_index")
     */
    public function users(): Response
    {
        return $this->render('admin/pages/user/index.html.twig');
    }
}
