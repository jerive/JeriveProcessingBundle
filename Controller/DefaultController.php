<?php

namespace Jerive\Bundle\FileProcessingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JeriveFileProcessingBundle:Default:index.html.twig', array('name' => $name));
    }
}
