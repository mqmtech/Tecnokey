<?php

namespace Tecnokey\ShopBundle\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\ShoppingCart;
use Tecnokey\ShopBundle\Form\Shop\ShoppingCartType;

/**
 * Shop\ShoppingCart controller.
 *
 * @Route("/crud/shoppingcart")
 */
class ShoppingCartController extends Controller
{
    /**
     * Lists all Shop\ShoppingCart entities.
     *
     * @Route("/", name="shop_shoppingcart")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Shop\ShoppingCart entity.
     *
     * @Route("/{id}/show", name="shop_shoppingcart_show")
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
     * @Route("/new", name="shop_shoppingcart_new")
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
     * @Route("/create", name="shop_shoppingcart_create")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\ShoppingCart:new.html.twig")
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

            return $this->redirect($this->generateUrl('shop_shoppingcart_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\ShoppingCart entity.
     *
     * @Route("/{id}/edit", name="shop_shoppingcart_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
        }

        $editForm = $this->createForm(new ShoppingCartType(), $entity);
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
     * @Route("/{id}/update", name="shop_shoppingcart_update")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\ShoppingCart:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\ShoppingCart entity.');
        }

        $editForm   = $this->createForm(new ShoppingCartType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            
            // Check if the quantity of an Entity is zero, then remove the entity
            $entity = $this->get('shoppingCartManager')->removeItemsWithoutProducts($entity);
            // End Check if the quantity of an Entity is zero
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('shop_shoppingcart_edit', array('id' => $id)));
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
     * @Route("/{id}/delete", name="shop_shoppingcart_delete")
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

        return $this->redirect($this->generateUrl('shop_shoppingcart'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
