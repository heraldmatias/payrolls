<?php

namespace Inei\Bundle\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('IneiAuthBundle:Default:index.html.twig', array('name' => $name));
    }
}
