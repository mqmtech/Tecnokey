<?php

namespace Tecnokey\ShopBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Form\Shop\ShoppingCartType;
/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/carrito")
 */
class ShoppingCartController extends Controller {

    /**
     * Frontend demo
     *
     * @Route("/", name="TKShopFrontendShoppingCartIndex")
     * @Template()
     */
    public function indexAction() {
        $shoppingCart = $this->getShoppingCartFromCurrentUser();
        
        return array(
            'shoppingCart' => $shoppingCart
        );       
    }
    
        /**
     * Finds and displays a Shop\ShoppingCart entity.
     *
     * @Route("/{id}/show", name="TKShopFrontendShoppingCartShow")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Shop\ShoppingCart entity.
     *
     * @Route("/new", name="TKShopFrontendShoppingCartNew")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ShoppingCart();
        $form   = $this->createForm(new ShoppingCartType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Shop\ShoppingCart entity.
     *
     * @Route("/create", name="TKShopFrontendShoppingCartCreate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Frontend\ShoppingCart:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new ShoppingCart();
        $request = $this->getRequest();
        $form    = $this->createForm(new ShoppingCartType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('TKShopFrontendShoppingCartShow', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\ShoppingCart entity.
     *
     * @Route("/{id}/edit", name="TKShopFrontendShoppingCartEdit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
        }

        //testing
        $viewModelFactory = $this->get('viewModelFactory');
        $shoppingCartViewModel = $viewModelFactory->create(new \Tecnokey\ShopBundle\ViewModel\Shop\ShoppingCartViewModelType(), $entity, array('entity' => $entity ));
        //end testing
        
        $editForm = $this->createForm(new ShoppingCartType(), $shoppingCartViewModel);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shop\ShoppingCart entity.
     *
     * @Route("/{id}/update", name="TKShopFrontendShoppingCartUpdate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Frontend\ShoppingCart:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
        }

        //testing
        $viewModelFactory = $this->get('viewModelFactory');
        $shoppingCartViewModel = $viewModelFactory->create(new \Tecnokey\ShopBundle\ViewModel\Shop\ShoppingCartViewModelType(), $entity, array('entity' => $entity ));
        //end testing
        
        $editForm   = $this->createForm(new ShoppingCartType(), $shoppingCartViewModel);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {

            // Check if the quantity of an Entity is zero, then remove the entity
            $entity = $this->get('shoppingCartManager')->removeItemsWithoutProducts($entity);
            // End Check if the quantity of an Entity is zero
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('TKShopFrontendShoppingCartEdit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shop\ShoppingCart entity.
     *
     * @Route("/{id}/delete", name="TKShopFrontendShoppingCartDelete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('TKShopFrontendShoppingCartIndex'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * @Route("/agregar_producto/{id}/", name="TKShopFrontendShoppingAddProduct")
     */
    public function addProductAction($id){
        $shoppingCart = $this->getShoppingCartFromCurrentUser();
        if($shoppingCart == NULL){
            return $this->shoppingCartErrorHandler();
        }
        
        //Get Product from DB
        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository("TecnokeyShopBundle:Shop\Product")->find($id);
        if($product == NULL){
            throw new Exception("Custom Exception: Product with id $id does NOT exist in database");
        }
        $this->get('shoppingCartManager')->addProductToCart($shoppingCart, $product);
        $em->persist($shoppingCart);
        $em->flush();
        
        return $this->redirect($this->generateUrl("TKShopFrontendShoppingCartIndex"));
    }
    
    /**
     * @Route("/limpiar_carrito/", name="TKShopFrontendShoppingCartRemoveAllItems")
     */
    public function removeAllProductsAction(){
        $shoppingCart = $this->getShoppingCartFromCurrentUser();
        if($shoppingCart == NULL){
            return $this->shoppingCartErrorHandler();
        }
        
        $this->get('shoppingCartManager')->removerAllItems($shoppingCart);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($shoppingCart);
        $em->flush();        
        
        return $this->redirect($this->generateUrl("TKShopFrontendShoppingCartIndex"));
    }
    
    //  HELPER FUNCTIONS  //
    
    /**
     * Redirects to home page if there is a problem with ShoppingCart
     *
     * @param type $msg
     * @param type $redirect
     * @return type 
     */
    protected function shoppingCartErrorHandler($msg = NULL, $redirect=NULL){
        //TODO: Redirect to index
        $this->get('session')->setFlash('shoppingCart_error',"Atencion: El usuario no dispone de carrito, cree un usuario de tipo cliente");
        return $this->redirect($this->generateUrl("TKShopFrontendIndex"));
    }
    
    /**
     * Get the ShoppingCart from the logged user
     * If the user does NOT have a shoppingCart then one is created and attached to user but not persisted to database
     * 
     * @return ShoppingCart 
     */
    protected function getShoppingCartFromCurrentUser(){
        $user = $this->get('userManager')->getCurrentUser();
        $shoppingCart = NULL;
        if($this->get('userManager')->isDBUser($user)){
            $shoppingCart = $user->getShoppingCart();
            if($shoppingCart == NULL){
                $shoppingCart = new ShoppingCart();
                $user->setShoppingCart($shoppingCart);
            }
        }
        return $shoppingCart;
    }

}
