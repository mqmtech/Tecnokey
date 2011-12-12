<?php

namespace Tecnokey\ShopBundle\Controller\Backend;

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

use MQMTech\ToolsBundle\Service\Pagination;

/**
 *
 * @Route("/admin/busqueda")
 */
class SearchController extends Controller {

    /**
     * TODO: Change it to POST for security!
     * @Route("/productos/por_multi_filtro/", name="TKShopBackendSearchProductsByMultiQuery")
     */
    public function searchProductByMultiQuery(){
        
        $request = Request::createFromGlobals();
        
        $method = $request->getMethod();

        $name = NULL;
        
        $query = NULL;
        if($method == 'POST'){
            $query = $request->request;
        }
        else{
            //TODO: Send Error msg
            $query = $request->query;
        }

        $name = $request->query->get('name');
        $description = $request->query->get('description');

        
        //Access to database
        $em = $this->getDoctrine()->getEntityManager();
        $products = $em->getRepository('TecnokeyShopBundle:Shop\Product')->searchByWordFull($name);
        if($products == NULL){
            //TODO: Lanzar pagina de errror?
        }
        
        //Set Pagination
        $pagination = NULL;
        if($products != NULL){
            $totalItemsLength = count($products);
            $pagination = $this->get('view.pagination')->calcPagination($totalItemsLength); 

            $products = $pagination->sliceArray($products);
        }
        //End Setting Pagination
        
        //set the delete form for every product
        $deleteForms = array();
        foreach ($products as $product) {
            $form = $this->createDeleteForm($product->getId());
            $deleteForms[$product->getId()] = $form->createView();
        }
        //end setting the delete form for every product

        return $this->render('TecnokeyShopBundle:Backend/Search:products.html.twig', 
                array('products' => $products, 
                      'deleteForms' => $deleteForms,
                      'pagination' => $pagination,
                      'search' => array('name' => $name)
            ));
    }
    
        /**
     * Utilities
     */
    
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
