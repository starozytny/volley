<?php

namespace App\Controller;

use App\Entity\Blog\BoArticle;
use Http\Discovery\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository(BoArticle::class)->findBy([], ['createdAt' => 'DESC'], 3);

        return $this->render('app/pages/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/legales/mentions-legales", name="app_mentions")
     */
    public function mentions(): Response
    {
        return $this->render('app/pages/legales/mentions.html.twig');
    }

    /**
     * @Route("/legales/politique-confidentialite", options={"expose"=true}, name="app_politique")
     */
    public function politique(): Response
    {
        return $this->render('app/pages/legales/politique.html.twig');
    }

    /**
     * @Route("/legales/cookies", name="app_cookies")
     */
    public function cookies(): Response
    {
        return $this->render('app/pages/legales/cookies.html.twig');
    }

    /**
     * @Route("/legales/rgpd", name="app_rgpd")
     */
    public function rgpd(): Response
    {
        return $this->render('app/pages/legales/rgpd.html.twig');
    }

    /**
     * @Route("/actualites", name="app_actualites")
     */
    public function actualites(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(BoArticle::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('app/pages/actualites/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/actualites/{slug}", options={"expose"=true}, name="app_actualites_read")
     */
    public function actualite($slug): Response
    {
        $em = $this->getDoctrine()->getManager();

        $obj = $em->getRepository(BoArticle::class)->findOneBy(['slug' => $slug]);
        if(!$obj){
            throw new NotFoundException("Cette article n'existe pas.");
        }

        $articles = $em->getRepository(BoArticle::class)->findBy([], ['createdAt' => 'DESC'], 3);

        return $this->render('app/pages/actualites/read.html.twig', [
            'elem' => $obj,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/adhesion", name="app_adhesion")
     */
    public function adhesion(): Response
    {
        return $this->render('app/pages/adhesion/index.html.twig');
    }

    /**
     * @Route("/nous-contacter", name="app_contact")
     */
    public function contact(): Response
    {
        return $this->render('app/pages/contact/index.html.twig');
    }
}
