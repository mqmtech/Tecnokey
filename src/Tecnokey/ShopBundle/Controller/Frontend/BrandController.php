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
use Tecnokey\ShopBundle\Repository\CategoryRepository;
use Tecnokey\ShopBundle\Repository\Repository;
use Symfony\Component\HttpFoundation\Request;
use MQMTech\ToolsBundle\Service\Pagination;
use Exception;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/marcas")
 */
class BrandController extends Controller {

    /**
     * @Route("/ver_todo.{_format}", defaults={"_format"="html"}, name="TKShopFrontendBrandsShowAll")
     */
    public function showAllAction($_format) {
        $em = $this->getDoctrine()->getEntityManager();
        
        //Grab selected product
        $brands = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->findAll();
        //End grabbing selected product

        
        return $this->render("TecnokeyShopBundle:Frontend\Brand:showAll.".$_format.".twig", array(
            'brands' => $brands,
            )
        );
    }
    
    /**
     * @Route("/ver_aleatorio.{_format}", defaults={"_format"="partialhtml"}, name="TKShopFrontendBrandsShowRandom")
     */
    public function showRandomAction($_format) {
        $em = $this->getDoctrine()->getEntityManager();
        
        //Grab selected product
        $brands = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->findRandom();
        //End grabbing selected product

        return $this->render("TecnokeyShopBundle:Frontend\Brand:showAll.".$_format.".twig", array(
            'brands' => $brands)
        );
    }
    
}
