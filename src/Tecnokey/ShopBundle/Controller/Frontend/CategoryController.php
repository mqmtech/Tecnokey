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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Exception;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/categorias")
 */
class CategoryController extends Controller {

    /**
     * Frontend demo
     *
     * @Route("/", name="TKShopFrontendCategoryIndex")
     * @Template()
     */
    public function indexAction() {
        
        //Grab cats from db
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAllFamilies();
        //End grabbing cats from db
        
        return array(
            'categories' => $categories,
        );
    }
    
    /**
     * @Route("/ver_familias_y_categorias.{_format}", defaults={"_format"="partialhtml"}, name="TKShopFrontendCategoriesShowAll")
     * @Template()
     */
    public function showAllAction() {
        $em = $this->getDoctrine()->getEntityManager();
        
        //Grab selected product
        $categories = $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAll();
        //End grabbing selected product
        
        return array(
            'categories' => $categories
        );
    }
    
    /**
     * @Route("/ver_familias.{_format}", defaults={"_format"="partialhtml"}, name="TKShopFrontendCategoriesShowAllFamilies")
     */
    public function showAllFamiliesAction($_format) {
        $em = $this->getDoctrine()->getEntityManager();
        
        //Grab selected product
        $categories = $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAllFamilies();
        //End grabbing selected product

        return $this->render("TecnokeyShopBundle:Frontend\Category:showAllFamilies.".$_format.".twig", array(
            'categories' => $categories)
                );
    }
    
    /**
     * @Route("/{id}/ver_categorias.{_format}", defaults={"_format"="html"}, name="TKShopFrontendCategoriesShowAllCategories")
     */
    public function showAllCategoriesAction($id, $_format) {
        $em = $this->getDoctrine()->getEntityManager();
        
        //Grab selected product
        $category = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);
        //End grabbing selected category
        if($category != null){
            $categories = $category->getCategories();
        }
        if($categories != NULL && count($categories) > 0 || $category->getParentCategory() == NULL){        
         return $this->render("TecnokeyShopBundle:Frontend\Category:showAllCategories.".$_format.".twig", array(
                'categories' => $categories,
                'parentCategory' => $category)
            );
         }
        else{
            //If No products then Redirect to the Frontend\ProductController:showByCategoryAction controller
            /*$response = $this->forward('TecnokeyShopBundle:Frontend\Product:showByCategory', array(
            'categoryId' => $id
            ));
            
            return $response;*/
            
            return $this->redirect($this->generateUrl('TKShopFrontendProductsShowByCategory', array(
            'categoryId' => $id
            )));
        }
    }
}
