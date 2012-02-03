<?php

namespace Tecnokey\ShopBundle\Controller\Frontend\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Form\Shop\ShoppingCartType;
use Symfony\Component\HttpFoundation\Request;
/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/usuario/carrito")
 */
class ShoppingCartController extends Controller {

    const FORM_ORDER_FIELD = "cart_order";
    const FORM_ORDER_UPDATE_VALUE= "cart_update";
    const FORM_ORDER_COMFIRM_VALUE = "cart_comfirm";
    
    /**
     * Frontend demo
     *
     * @Route("/", name="TKShopFrontendUserShoppingCartIndex")
     * @Template()
     */
    public function indexAction() {
        /*$shoppingCart = $this->getShoppingCartFromCurrentUser();
        
        return array(
            'shoppingCart' => $shoppingCart
        );*/
        
        return $this->redirect($this->generateUrl('TKShopFrontendUserShoppingCartEdit'));
        //return $this->forward('TecnokeyShopBundle:Frontend\User\ShoppingCart:edit');
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
            'delete_form' => $deleteForm->createView(),       
        );
    }

    /**
     * Displays a form to create a new Shop\ShoppingCart entity.
     *
     * @Route("/new", name="TKShopFrontendUserShoppingCartNew")
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
     * @Route("/create", name="TKShopFrontendUserShoppingCartCreate")
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
     * @Route("/editar", name="TKShopFrontendUserShoppingCartEdit")
     */
    public function editAction(){
        $entity = $this->getShoppingCartFromCurrentUser();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
            /*$this->get('session')->setFlash('shoppingCart_error',"Atencion: El usuario no dispone de carrito de la compra");
            return $this->redirect($this->generateUrl("TKShopFrontendIndex"));*/
        }
        
        // Fill Shopping Cart with the current last Market/Offer Info
        $checkoutManager = $this->get('checkoutManager');
        $entity = $checkoutManager->checkout($entity);
        // End filling Shopping Cart
        
        $editForm = $this->createForm(new ShoppingCartType(), $entity);
        $deleteForm = $this->createDeleteForm($entity->getId());
        
        return $this->render("TecnokeyShopBundle:Frontend\User\ShoppingCart:edit.html.twig", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
            'order_field' => self::FORM_ORDER_FIELD,
            'update_value' => self::FORM_ORDER_UPDATE_VALUE,
            'comfirm_value' => self::FORM_ORDER_COMFIRM_VALUE,
        ));
    }
    
    /**
     * Displays a form to edit an existing Shop\ShoppingCart entity.
     *
     * @Route("/item/{id}/eliminar", name="TKShopFrontendUserShoppingCartDeleteItem")
     */
    public function deleteItemAction($id){
        $shoppingCart = $this->getShoppingCartFromCurrentUser();
        if($shoppingCart != NULL){
            
            $this->get('shoppingCartManager')->removeItem($shoppingCart, $id);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($shoppingCart);
            $em->flush();
            
            return $this->redirect($this->generateUrl('TKShopFrontendUserShoppingCartEdit', array(
            'id' => $shoppingCart->getId()
            )));
            /*return $this->forward("TecnokeyShopBundle:Frontend\User\ShoppingCart:edit", array(
            'id' => $shoppingCart->getId()
            ));*/            
        }
        else{
            $this->get('session')->setFlash('shoppingCart_error',"Atencion: El usuario no dispone de carrito de la compra");
            return $this->redirect($this->generateUrl("TKShopFrontendIndex"));
        }
    }
    
    /**
     * Deletes a Shop\ShoppingCart entity.
     *
     * @Route("/delete", name="TKShopFrontendUserShoppingCartDelete")
     * @Method("post")
     */
    public function deleteAction()
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $this->getShoppingCartFromCurrentUser();
            //$entity = $em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('TKShopFrontendUserShoppingCartIndex'));
    }

    /**
     * Edits an existing Shop\ShoppingCart entity.
     *
     * @Route("/update", name="TKShopFrontendUserShoppingCartUpdate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Frontend\User\ShoppingCart:edit.html.twig")
     */
    public function updateAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $this->getShoppingCartFromCurrentUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
        }
        
        $editForm   = $this->createForm(new ShoppingCartType(), $entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            // Check if the quantity of an Entity is zero, then remove the entity
            $entity = $this->get('shoppingCartManager')->removeItemsWithoutProducts($entity);
            // End Check if the quantity of an Entity is zero
            
            $em->persist($entity);
            $em->flush();
            
            //Get extra data to see if the cart must just updated or must be ordered
            $request = Request::createFromGlobals();
            $order = $request->request->get(self::FORM_ORDER_FIELD);
            if($order == self::FORM_ORDER_COMFIRM_VALUE){
                // generate an order
                    $checkoutManager = $this->get('checkoutManager');
                    $order = $checkoutManager->shoppingCartToOrder($entity);
                    
                    $user = $this->get('userManager')->getCurrentUser();
                    
                    $order->setUser($user);
                    
                    $em->persist($order);
                    $em->flush();
                // remove cart
                //$em->remove($entity);
                //$em->flush();
            
                // redirect to an order page                
            }
            //End get extra data

            return $this->redirect($this->generateUrl('TKShopFrontendUserShoppingCartEdit'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * @Route("/agregar_producto/{id}/", name="TKShopFrontendUserShoppingAddProduct")
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
        
        return $this->redirect($this->generateUrl("TKShopFrontendUserShoppingCartIndex"));
    }
    
    /**
     * @Route("/limpiar_carrito/", name="TKShopFrontendUserShoppingCartRemoveAllItems")
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
        
        return $this->redirect($this->generateUrl("TKShopFrontendUserShoppingCartIndex"));
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
