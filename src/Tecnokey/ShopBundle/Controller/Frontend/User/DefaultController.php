<?php

namespace Tecnokey\ShopBundle\Controller\Frontend\User;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Form\Shop\UserType;
use Tecnokey\ShopBundle\Entity\Shop\User;
use Exception;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/usuario")
 */
class DefaultController extends Controller {

    /**
     * Frontend demo
     *
     * @Route("/", name="TKShopFrontendUserIndex")
     * @Template()
     */
    public function indexAction() {
        return $this->redirect($this->generateUrl('TKShopBackendPanelControlIndex'));        
    }
    
    /**
     * Frontend demo
     *
     * @Route("/ver.{_format}", defaults={"_format"="html"}, name="TKShopFrontendUserShow")
     * @Template()
     */
    public function showAction($_format){
        
        return $this->render("TecnokeyShopBundle:Frontend\User\Default:show.".$_format.".twig", array('lastUser' => "admin"));
    } 

}
