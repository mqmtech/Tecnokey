<?php

namespace Tecnokey\ShopBundle\Controller\Frontend;

use Tecnokey\ShopBundle\Entity\Shop\Brand;
use Tecnokey\ShopBundle\Entity\Shop\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Category;
use Tecnokey\ShopBundle\Form\Shop\CategoryType;
use Exception;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/usuarios")
 */
class UserController extends Controller {

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
     * @Route("/login.{_format}", defaults={"_format"="partialhtml"}, name="TKShopFrontendUserLogin")
     * @Template()
     */
    public function loginAction(){
        return array(
            "lastUser" => "admin"
        );
    }    
    
     /**
     * Frontend demo
     *
     * @Route("/auto_login.{_format}", defaults={"_format"="html"}, name="TKShopFrontendUserAutoLogin")
     */
    public function autoLoginAction(){
        
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository("TecnokeyShopBundle:Shop\User")->find(1);
        
        // create the authentication token
        $token = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles());
        // give it to the security context
        $this->container->get('security.context')->setToken($token);

        return $this->redirect($this->generateUrl("TKShopFrontendIndex"));
    }

}
