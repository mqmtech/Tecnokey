<?php

namespace Tecnokey\ShopBundle\Controller\Backend;

use Tecnokey\ShopBundle\Entity\Shop\Brand;
use Tecnokey\ShopBundle\Entity\Shop\Category;
use Tecnokey\ShopBundle\Entity\Shop\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Form\Shop\ProductType;
use Exception;

use MQMTech\ToolsBundle\Service\Pagination;

/**
 * Frontend\Default controller.
 *
 * @Route("/admin/productos")
 */
class ProductController extends Controller {

    /**
     * Backend Products
     *
     * @Route("/", name="TKShopBackendProductsIndex")
     * @Template()
     */
    public function indexAction() {
        return array();        
    }
    
    /**
     * Backend product
     *
     * @Route("/~/nuevo", name="TKShopBackendProductNew")
     * @Template()
     */
    public function newAction() {
        
        //Get categories to help building the Form
        $em = $this->getDoctrine()->getEntityManager();
        $categories= $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAll();
        //End
        
        //Get brands to help building the Form
        $brands= $em->getRepository('TecnokeyShopBundle:Shop\Brand')->findAll();
        //End

        $entity = new Product();
        //Creating the Form
        $productType = $this->get('form.type.product');
        $form   = $this->createForm($productType, $entity);
        

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );        
    }
    
    /**
     * Creates a new Shop\Product entity.
     *
     * @Route("/create", name="TKShopBackendProductCreate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Backend\Product:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Product();
        $request = $this->getRequest();
        $productType = $this->get('form.type.product');
        $form   = $this->createForm($productType, $entity);
        $form->bindRequest($request);

        if (false || $form->isValid()) { //// TRUE value is a  TEMPORAL SOLUTION OF DATETIME PROBLEM-> SOLVE SOON  ////
            $em = $this->getDoctrine()->getEntityManager();
           
            $em->persist($entity);
            $em->flush();
            
            //Upload file (not needed->done by ORMLifeCyle)
            //$image = $entity->getImage();
            //$image->upload();
            //End Uploading file

            //return $this->redirect($this->generateUrl('TKShopBackendProductShow', array('productId' => $entity->getId())));
            return $this->redirect($this->generateUrl('TKShopBackendProductsShowAll'));
            
        }
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }
    
    /**
     * @Route("/{productId}/editar", name="TKShopBackendProductEdit")
     * @Template("TecnokeyShopBundle:Backend\Product:edit.html.twig")
     */
    public function editAction($productId)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($productId);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Product entity.');
        }
        
        //FIX image VIRTUAL file field
        /*$image = $entity->getImage();
        if($image != null){
            $image->setFile($image->getId().'.'.$image->getPath());
        }
        
        $image = $entity->getSecondImage();
        if($image != null){
            $image->setFile($image->getId().'.'.$image->getPath());
        }
        
        $image = $entity->getThirdImage();
        if($image != null){
            $image->setFile($image->getId().'.'.$image->getPath());
        }
        
        $image = $entity->getFourthImage();
        if($image != null){
            $image->setFile($image->getId().'.'.$image->getPath());
        }*/     
        //End FIX image VIRTUAL file field
        
        //test
        $categories = $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAll();
        //endtest
        
        $productType = $this->get('form.type.product');
        $editForm   = $this->createForm($productType, $entity);
        $deleteForm = $this->createDeleteForm($productId);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
        
    }
    
        /**
     * Displays a form to edit an existing Shop\Product entity.
     *
     * @Route("/{productId}/clonar", name="TKShopBackendProductClone")
     * @Template("TecnokeyShopBundle:Backend\Product:new.html.twig")
     */
    public function cloneAction($productId)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($productId);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Product entity.');
        }
        
        $entityCloned = clone ($entity);
        
        $productType = $this->get('form.type.product');
        $editForm   = $this->createForm($productType, $entityCloned);

        return array(
            'entity'      => $entityCloned,
            'form'   => $editForm->createView(),
        );
        
    }
    
    /**
     * Deletes a Shop\Product entity.
     *
     * @Route("/{productId}/eliminar", name="TKShopBackendProductDelete")
     * @Method("post")
     */
    public function deleteAction($productId)
    {
        $form = $this->createDeleteForm($productId);
        $request = $this->getRequest();

        $form->bindRequest($request);
        
        if (false || $form->isValid()) { //// TRUE value is a  TEMPORAL SOLUTION OF DATETIME PROBLEM-> SOLVE SOON  ////
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($productId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\Product entity.');
            }

            $em->remove($entity);
            $em->flush();
                    
            return $this->redirect($this->generateUrl('TKShopBackendProductsShowAll'));
        }
        else{
            $content = $this->renderView('TecnokeyShopBundle:Backend\Product:index.html.twig', array('error' => 'El producto no puede ser eliminado'));
            return $content;
        }
    }
    
    /**
     * Edits an existing Shop\Product entity.
     *
     * @Route("/{productId}/actualizar", name="TKShopBackendProductUpdate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Backend\Product:edit.html.twig")
     */
    public function updateAction($productId)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($productId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Product entity.');
        }

        $productType = $this->get('form.type.product');
        $editForm   = $this->createForm($productType, $entity);
        $deleteForm = $this->createDeleteForm($productId);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if (false || $editForm->isValid()) {//// TRUE value is a  TEMPORAL SOLUTION OF DATETIME PROBLEM-> SOLVE SOON  ////
            
            //FIX VIRTUAL file update in Image
            $image = $entity->getImage();
            if($image != null){
                if($image->isFileUpdated() == false){
                    $image->setFileUpdated(true);
                }
                else{
                    $image->setFileUpdated(false);
                }
                    
            }

            $image = $entity->getSecondImage();
            if($image != null){
                if($image->isFileUpdated() == false){
                    $image->setFileUpdated(true);
                }
                else{
                    $image->setFileUpdated(false);
                }
            }

            $image = $entity->getThirdImage();
            if($image != null){
                if($image->isFileUpdated() == false){
                    $image->setFileUpdated(true);
                }
                else{
                    $image->setFileUpdated(false);
                }
            }

            $image = $entity->getFourthImage();
            if($image != null){
                if($image->isFileUpdated() == false){
                    $image->setFileUpdated(true);
                }
                else{
                    $image->setFileUpdated(false);
                }
            }
            //End FIX VIRTUAL file update in Image
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('TKShopBackendProductsShowAll'));
        }
        
        throw new Exception("Custom Exception: Form not validated");

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
     /**
     * Backend product
     *
     * @Route("/administracion/ver_todos", name="TKShopBackendProductsShowAll")
     * @Template()
     */
    public function showAllAction() {
        //Grab products from db
        $em = $this->getDoctrine()->getEntityManager();
        $products = $em->getRepository('TecnokeyShopBundle:Shop\Product')->findAll();
        //End grabbing prods from db
        
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
        
        return array(
            'products' => $products,
            'deleteForms' => $deleteForms,
            'pagination' => $pagination
        );
    }
    
    /**
     *
     * @Route("/administracion/por_categoria", name="TKShopBackendProductsCategoriesShowAll")
     * @Template("TecnokeyShopBundle:Backend\Product:showAllCategories.html.twig")
     */
    public function showAllCategoriesAction() {
        //Grab products from db
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAllFamilies();
        //End grabbing prods from db
        
        //Set Pagination
        $pagination = NULL;
        if($categories != NULL){
            $totalItemsLength = count($categories);
            $pagination = $this->get('view.pagination')->calcPagination($totalItemsLength); 
            $categories = $pagination->sliceArray($categories);
        }
        //End Setting Pagination
        
        //set the delete form for every product
        $deleteForms = array();
        foreach ($categories as $category) {
            $form = $this->createDeleteForm($category->getId());
            $deleteForms[$category->getId()] = $form->createView();
        }

        //end setting the delete form for every product
        
        return array(
            'categories' => $categories,
            'deleteForms' => $deleteForms,
            'pagination' => $pagination,
        );      
    }
    
    /**
     *
     * @Route("/administracion/por_categoria/subcategoria/{id}", name="TKShopBackendProductsShowAllSubcategories")
     * @Template("TecnokeyShopBundle:Backend\Product:showAllCategories.html.twig")
     */
    public function showAllSubCategoriesAction($id) {
        //Grab products from db
        $em = $this->getDoctrine()->getEntityManager();
        $category = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);
        $categories = $category->getCategories();
        
        if($categories == NULL || count($categories) == 0){
            //redirect to show products
            return $this->redirect($this->generateUrl('TKShopBackendProductsShowByCategoryAction', array('id' => $category->getId())));
        }
        //End grabbing prods from db
        
        //set the delete form for every product
        $deleteForms = array();
        foreach ($categories as $category) {
            $form = $this->createDeleteForm($category->getId());
            $deleteForms[$category->getId()] = $form->createView();
        }

        //end setting the delete form for every product
        
        return array(
            'categories' => $categories,
            'deleteForms' => $deleteForms,
        );      
    }
    
    /**
     * Backend product
     *
     * @Route("/administracion/por_categoria/{id}", name="TKShopBackendProductsShowByCategoryAction")
     * @Template("TecnokeyShopBundle:Backend\Product:showAll.html.twig")
     */
    public function showByCategoryAction($id) {
        //Grab products from db
        $em = $this->getDoctrine()->getEntityManager();
        $category = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);
        $products = $category->getProducts();
        //End grabbing prods from db
        
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
        
        
        return array(
            'products' => $products,
            'deleteForms' => $deleteForms,
            'pagination' => $pagination,
        );
    }
    
    /**
     * @Route("/recientes.{_format}", defaults={"_format"="partialhtml"}, name="TKShopBackendProductsRecent")
     * @Template()
     */
    public function recentAction($_format){
        $em = $this->getDoctrine()->getEntityManager();
        
        $products = $em->getRepository("TecnokeyShopBundle:Shop\Product")->findRecent();
        
        //return $this->render("TecnokeyShopBundle:Frontend\Product:recent.".$_format.".twig", array('products' => $products));
        return array(
            'products' => $products
        );

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
