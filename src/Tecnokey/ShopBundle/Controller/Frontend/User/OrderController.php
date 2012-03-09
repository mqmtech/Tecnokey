<?php

namespace Tecnokey\ShopBundle\Controller\Frontend\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Order;
use Tecnokey\ShopBundle\Form\Shop\OrderType;

/**
 * Shop\Order controller.
 *
 * @Route("/tienda/usuario/pedidos")
 */
class OrderController extends Controller
{
    /**
     * Lists all Shop\Order entities.
     *
     * @Route("/", name="TKShopFrontendOrderIndex")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\Order')->findAll();

        return array('entities' => $entities);
    }
    
     /**
     * 
     *
     * @Route("/realizados", name="TKShopFrontendOrdersShowDelivered")
     * @Template()
     */
    public function showDeliveredAction()
    {
        
        $orderBy = $this->get('view.sort');
        $orderBy->add('pedido', 'publicId', 'pedido')
                ->add('fecha', 'created_at', 'Fecha')
                ->add('cantidad', 'quantity', 'Cantidad')
                ->add('importe', 'totalPrice', 'Importe')
                ->add('estado', 'status', 'Estado')
                ->initialize();
        
        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('userManager')->getCurrentUser();
        $entities = $em->getRepository('TecnokeyShopBundle:Shop\\User')->findDeliveredOrders($user, $orderBy->getValues());

        return array(
            'entities' => $entities,
            'orderBy' => $orderBy->switchMode(),
        );
    }
    
    /**
     * 
     *
     * @Route("/en_proceso", name="TKShopFrontendOrdersShowInProcess")
     * @Template()
     */
    public function showInProcessAction()
    {
        $orderBy = $this->get('view.sort');
        $orderBy->add('pedido', 'publicId', 'pedido')
                ->add('fecha', 'createdAt', 'Fecha')
                ->add('cantidad', 'quantity', 'Cantidad')
                ->add('importe', 'totalPrice', 'Importe')
                ->add('estado', 'status', 'Estado')
                ->initialize();
        
        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('userManager')->getCurrentUser();
        $entities = $em->getRepository('TecnokeyShopBundle:Shop\\User')->findInProcessOrders($user, $orderBy->getValues());

        return array(
            'entities' => $entities,
            'orderBy' => $orderBy->switchMode(),
        );
    }

    /**
     * Finds and displays a Shop\Order entity.
     *
     * @Route("/{publicId}/show", name="TKShopFrontendOrderShow")
     * @Template()
     */
    public function showAction($publicId)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Order')->findByPublicId($publicId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Order entity.');
        }

        return array(
            'order'      => $entity,
        );
    }
    
    /**
     * Finds and displays a Shop\Order entity.
     *
     * @Route("/confirmar", name="TKShopFrontendOrderCreateFromShoppingCart")
     * @Template()
     */
    public function createFromShoppingCartAction()
    {
        $confirmed = $this->confirmOrder();
        
        if ($confirmed == true) {
                return $this->redirect($this->generateUrl('TKShopFrontendOrdersShowInProcess'));
        }
        
        else{
            return $this->redirect($this->generateUrl('TKShopFrontendUserShoppingCartEdit'));
        }
    }
  
    //  HELPER FUNCTIONS  //
    
    /**
     * Redirects to home page if there is a problem with Oder
     *
     * @param type $msg
     * @param type $redirect
     * @return type 
     */
    protected function orderErrorHandler($msg = NULL, $redirect=NULL){
        //TODO: Redirect to index
        $this->get('session')->setFlash('order_error',"Atencion: El usuario no puede tener pedidos, cree un usuario de tipo cliente");
        return $this->redirect($this->generateUrl("TKShopFrontendIndex"));
    }
    
    /**
     * Get the Order from the logged user
     * 
     * @return Order 
     */
    protected function getOrderFromCurrentUser(){
        $user = $this->get('userManager')->getCurrentUser();
        $shoppingCart = NULL;
        if($this->get('userManager')->isDBUser($user)){
            return $user->getOrders();            
        }
        else{
            return NULL;
        }
    }
    
    
    /**
     *
     * @param ShoppingCart $shoppingCart
     * @return boolean 
     */
    public function confirmOrder() {
        
        $sc = $this->getUserShoppingCart();

        $items = $sc->getItems();
        if (count($items) < 1) {
            return false;
        }
        try {
            $checkoutManager = $this->get('checkoutManager');
            $sc = $checkoutManager->checkout($sc);
            // generate an order
            $order = $checkoutManager->shoppingCartToOrder($sc);

            $user = $this->get('userManager')->getCurrentUser();

            $order->setUser($user);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($order);
            $em->flush();
            //End generating an order
            // remove all cart items
            $this->get('shoppingCartManager')->removeAllItems($sc);
            $em->flush();
            //End removing all cart items
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get the ShoppingCart from the logged user
     * If the user does NOT have a shoppingCart then one is created and attached to user but not persisted to database
     * 
     * @return ShoppingCart 
     */
    protected function getUserShoppingCart() {
        $user = $this->get('userManager')->getCurrentUser();
        
        $shoppingCart = NULL;
        if ($this->get('userManager')->isDBUser($user)) {
            $shoppingCart = $user->getShoppingCart();
            if ($shoppingCart == NULL) {
                $shoppingCart = new ShoppingCart();
                $user->setShoppingCart($shoppingCart);
            }
        } 
        
        return $shoppingCart;
    }
}
