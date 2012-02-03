<?php

namespace Tecnokey\ShopBundle\Controller\Backend\Security;

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
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Exception;

/**
 * Frontend\Default controller.
 *
 * @Route("/admin/seguridad")
 */
class DefaultController extends Controller {
    
    /**
     * @Route("/login", name="TKShopBackendSecurityLogin")
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('TecnokeyShopBundle:Backend\Security\Default:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    
    /**
     * @Route("/login_check", name="TKShopBackendSecurityLoginCheck")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }
    
    /**
     * @Route("/logout", name="TKShopBackendSecurityLogout")
     */
    public function securityLogoutAction()
    {
        // The security layer will intercept this request
    }
}
