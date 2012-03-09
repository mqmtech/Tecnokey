<?php

namespace Tecnokey\ShopBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Tecnokey\ShopBundle\Entity\Shop\Product;
use Tecnokey\ShopBundle\Entity\Shop\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Exception;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/busqueda")
 */
class SearchController extends Controller {
    
    /**
     * @Route("/productos/por_marca/{id}", name="TKShopFrontendSearchProductsByBrand")
     */
    public function searchProductByBrand($id){
        
        $errors = NULL;
        
        $orderBy = $this->get('view.sort');
        $orderBy->add('name', 'name', 'Producto')
                ->add('price', 'basePrice', 'Precio')
                ->initialize();
        
        $em = $this->getDoctrine()->getEntityManager();
        $brand = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->find($id);
        $products = $em->getRepository('TecnokeyShopBundle:Shop\Product')->findByBrandId($id, $orderBy->getValues());
        $productsInfo = NULL;
        $pagination = NULL;
        if($products == NULL){
            $errors = array('404' => "There's no brand with the id: " . $id);
            //TODO: Lanzar pagina de errror?
        }
        else{
            //Set Pagination
            $pagination = NULL;
            if($products != NULL){
                $totalItemsLength = count($products);
                $pagination = $this->get('view.pagination')->calcPagination($totalItemsLength); 
                $products = $pagination->sliceArray($products);
            }
            //End Setting Pagination
            
            $productsInfo = $this->get('productManager')->getProductsPriceInfo($products);
        }
        
        return $this->render('TecnokeyShopBundle:Frontend/Search:products.html.twig', 
                array('products' => $products,
                    'productsInfo' => $productsInfo,
                    'orderBy' => $orderBy->switchMode(),
                    'search' => array('name' => $brand->getName()),
                    'pagination' => $pagination
                    ));
        
    }   
    
    
    /**
     * TODO: Change it to POST for security!
     * @Route("/productos/por_multi_filtro/", name="TKShopFrontendSearchProductsByMultiQuery")
     */
    public function searchProductByMultiQuery(){

        $request = Request::createFromGlobals();
        $method = $request->getMethod();
        $query = NULL;
        if($method == 'POST'){
            $query = $request->request;
        }
        else{
            //TODO: Send Error msg
            $query = $request->query;
        }
        
        $name = $query->get('name');
        
        $orderBy = $this->get('view.sort');
        $orderBy->add('name', 'name', 'Producto')
                ->add('price', 'basePrice', 'Precio')
                ->initialize();
        
        //Access to database for count
        $em = $this->getDoctrine()->getEntityManager();
        $totalItemsLength = $em->getRepository('TecnokeyShopBundle:Shop\Product')->searchByWordFullCount($name);
        //End access to database for count
        
        //Set Pagination
        $pagination = $this->get('view.pagination')->calcPagination($totalItemsLength); 
        $products = null;
        if($pagination != NULL){
            $currentRange = $pagination->getCurrentRange();
            $products = $em->getRepository('TecnokeyShopBundle:Shop\Product')->searchByWordFull($name, "OR", $orderBy->getValues(), $currentRange['offset'], $currentRange['lenght']);
            
        }
        //End Setting Pagination
        
        if($products == NULL){
            //TODO: Lanzar pagina de errror?
        }
        else{
            $productsInfo = $this->get('productManager')->getProductsPriceInfo($products);
        }

        return $this->render('TecnokeyShopBundle:Frontend/Search:products.html.twig', 
                array('products' => $products, 
                      'productsInfo' => $productsInfo,
                      'pagination' => $pagination,
                      'search' => array('name' => $name),
                      'orderBy' => $orderBy->switchMode(),
            ));
    }
}
