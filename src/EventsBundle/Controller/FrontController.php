<?php

namespace EventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Events/Default/index.html.twig');
    }
    public function AfficherAction()
    {

        return $this->render("@Events/Default/affichage.html.twig"
        );
    }
}
