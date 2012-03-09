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
use Exception;
use MQMTech\ToolsBundle\IO\PropertiesReader;
use Tecnokey\ShopBundle\Service\ProductManager;

use Symfony\Component\HttpFoundation\Request;

use Tecnokey\ShopBundle\Entity\Statistic\ProductStatistic;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/productos")
 */
class ProductController extends Controller {

   
    /**
     * Frontend demo
     *
     * @Route("/", name="TKShopFrontendProductIndex")
     * @Template()
     */
    public function indexAction() {
        //return $this->redirect($this->generateUrl("TKShopFrontendProductShow"));
        return array();
    }
    
    /**
     * Frontend demo
     *
     * @Route("/por_categoria/{categoryId}.{_format}", defaults={"_format"="html"}, name="TKShopFrontendProductsShowByCategory")
     * @Template()
     */
    public function showByCategoryAction($categoryId) {
        $em = $this->getDoctrine()->getEntityManager();
        
        $orderBy = $this->get('view.sort');
        $orderBy->add('name', 'name', 'Producto')
                ->add('price', 'basePrice', 'Precio')
                ->initialize();
        
        //Grab category from db
        $category = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($categoryId);
        //End grabbing cat from db
        
        if($category == NULL){
            throw new Exception("Custom Exception: Category is NULL");
        }
        $rootCategory = $category->getRootCategory();
        
        //Grab products  from db
        $products = $em->getRepository('TecnokeyShopBundle:Shop\Product')->findByCategoryId($categoryId, $orderBy->getValues());
        //End grabbing prods from db
        
        //Set Pagination
        $pagination = NULL;
        if($products != NULL){
            $totalItemsLength = count($products);
            $pagination = $this->get('view.pagination')->calcPagination($totalItemsLength); 
            $products = $pagination->sliceArray($products);
            
            //$range = $pagination->getCurrentRange();
        }
        //End Setting Pagination
        
        $productsInfo = $this->get('productManager')->getProductsPriceInfo($products);
        
        return array(
            'rootCategory' => $rootCategory,
            'category' => $category,
            'products' => $products,
            'productsInfo' => $productsInfo,
            'pagination' => $pagination,
            'orderBy' => $orderBy->switchMode(),
        );
    }
    
    /**
     *
     * @param integer $productId
     * @return HttpResponse
     * @Route("/{productId}/productos_relacionados/", defaults={"_format" = "partialhtml"}, name = "TKShopBundleFrontendProductRelatedProducts") 
     * @Template()
     */
    public function relatedProductsAction($productId){
        $em = $this->getDoctrine()->getEntityManager();
        
        $relatedProducts = $em->getRepository('TecnokeyShopBundle:Shop\Product')->findRelatedProducts($productId);
        
        $relatedProductsInfo = $this->get('productManager')->getProductsPriceInfo($relatedProducts);
        
        return array(
            'products' => $relatedProducts,
            'productsInfo' => $relatedProductsInfo
        );
    }

     /**
     * Single products actions:
     */

     /**
     * Frontend Product
     *
     * @Route("/{productId}/ver", name="TKShopFrontendProductShow")
     * @Template()
     */
    public function showAction($productId) {
        $em = $this->getDoctrine()->getEntityManager();
        
        //Grab selected product
        $product = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($productId);
        //End grabbing selected product
        
        if($product == NULL){
           throw $this->createNotFoundException("Custom Exception: Product Not Found");
        }
        
        $relatedCategory = $product->getCategory();
        
        $breadcrumb = array();
        $i=0; $maxBreadcrumb = 6;
        while ($i < $maxBreadcrumb) {
            if($relatedCategory != NULL){
                array_unshift($breadcrumb, $relatedCategory);  
                $relatedCategory = $relatedCategory->getParentCategory();
            }
            else{
                break;
            }
            $i++;
        }
        
        $productPriceInfo = $this->get('productManager')->getProductPriceInfo($product);    
        
        //Set Statistics
        $productStatistic = $this->registerProductStatistic($product);
        $em->persist($productStatistic);
        $em->flush();
        //End Setting Statistics
        return array(
            'breadcrumb' => $breadcrumb,
            'product' => $product,
            'productInfo' => $productPriceInfo
        );
    }    
    
    /**
     *
     * @param Product $product
     * @return ProductStatistic 
     */
    public function registerProductStatistic(Product $product){
        $productStatistic = new ProductStatistic();
        $productStatistic->setType(\Tecnokey\ShopBundle\Entity\Statistic\Statistic::PRODUCT_TYPE_SEEN);
        
        $this->get("productStatistic")->registerStatToProduct($productStatistic, $product);
        
        return $productStatistic;
    }
}
