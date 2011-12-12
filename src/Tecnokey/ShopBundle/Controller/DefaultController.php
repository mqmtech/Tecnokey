<?php

namespace Tecnokey\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
    
     /**
     * Return static pages
     * @param type $page
     * @return type 
     */
    public function staticAction($page)
    {
        //return new Response ( "Static Routing to page: ". $page );
        //return $this->render("TecnokeyShopBundle:Default:".$page.".html.twig");
        return $this->render("TecnokeyShopBundle:Default:$page.html.twig");
    }
}
