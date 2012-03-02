<?php

namespace Tecnokey\ShopBundle\Controller\Frontend\Security;

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
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/seguridad")
 */
class DefaultController extends Controller {
    
    /**
     * @Route("/login", name="TKShopFrontendSecurityLogin")
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

        return $this->render('TecnokeyShopBundle:Frontend\Security\Default:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    
    /**
     *
     * @Route("/auto_login.{_format}", defaults={"_format"="html"}, name="TKShopFrontendSecurityAutoLogin")
     */
    public function autoLoginAction() {

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
    
    /**
     * @Route("/login_check", name="TKShopFrontendSecurityLoginCheck")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }
    
    /**
     * @Route("/logout", name="TKShopFrontendSecurityLogout")
     */
    public function securityLogoutAction()
    {
        // The security layer will intercept this request
    }
}
