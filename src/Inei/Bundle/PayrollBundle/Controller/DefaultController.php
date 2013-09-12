<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('IneiPayrollBundle:Default:index.html.twig', array('name' => $name));
    }
}
