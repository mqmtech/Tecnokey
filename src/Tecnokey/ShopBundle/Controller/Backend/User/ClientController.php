<?php

namespace Tecnokey\ShopBundle\Controller\Backend\User;

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

/**
 * Frontend\Default controller.
 *
 * @Route("/admin/usuarios/clientes")
 */
class ClientController extends Controller {
    
    /**
     * Backend Productos
     *
     * @Route("/", name="TKShopBackendUserClientIndex")
     * @Template()
     */
    public function indexAction() {
        return array('name' => "Usuario!");
    }
    
    
    /**
     * @Route("/recientes.{_format}", defaults={"_format"="partialhtml"}, name="TKShopBackendUserClientRecent")
     * @Template()
     */
    public function recentClientsAction($_format){
        $em = $this->getDoctrine()->getEntityManager();
        
        $clients = $em->getRepository("TecnokeyShopBundle:Shop\User")->findRecentClients();
        
        return $this->render("TecnokeyShopBundle:Backend\User\Client:recentClients.".$_format.".twig", array('clients' => $clients));
    }

}
