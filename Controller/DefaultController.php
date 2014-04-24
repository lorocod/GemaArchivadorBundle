<?php

namespace Gema\ArchivadorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
       // echo '<pre>';
        var_dump($this->container->parameters );
       // echo '</pre>';
        return array();
    }

    /**
     * @Route("/estilo1")
     * @Template()
     */
    public function estilo1Action()
    {
        return array();
    }
}
