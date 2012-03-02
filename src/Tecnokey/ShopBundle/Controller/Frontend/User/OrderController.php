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
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\Order')->findDelivered();

        return array(
            'entities' => $entities,
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
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\Order')->findInProcess();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Shop\Order entity.
     *
     * @Route("/{id}/show", name="TKShopFrontendOrderShow")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Order entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Shop\Order entity.
     *
     * @Route("/new", name="TKShopFrontendOrderNew")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Order();
        $form   = $this->createForm(new OrderType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Shop\Order entity.
     *
     * @Route("/create", name="TKShopFrontendOrderCreate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Frontend\User\Order:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Order();
        $request = $this->getRequest();
        $form    = $this->createForm(new OrderType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('TKShopFrontendOrderCartShow', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\Order entity.
     *
     * @Route("/{id}/edit", name="TKShopFrontendOrderEdit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Order entity.');
        }

        $editForm = $this->createForm(new OrderType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shop\Order entity.
     *
     * @Route("/{id}/update", name="TKShopFrontendOrderUpdate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\Order:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Order entity.');
        }

        $editForm   = $this->createForm(new OrderType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_order_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shop\Order entity.
     *
     * @Route("/{id}/delete", name="TKShopFrontendOrderDelete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Shop\Order')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\Order entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_order'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
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
}
