<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/styleguide", name="admin_")
 */
class StyleguideController extends AbstractController
{
    /**
     * @Route("/", name="styleguide")
     */
    public function index(): Response
    {
        return $this->render('admin/pages/styleguide/index.html.twig', [
            'controller_name' => 'StyleguideController',
        ]);
    }
}
