<?php

namespace Tecnokey\ShopBundle\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Product;
use Tecnokey\ShopBundle\Form\Shop\ProductType;

/**
 * Shop\Product controller.
 *
 * @Route("/crud/product")
 */
class ProductController extends Controller
{
    /**
     * Lists all Shop\Product entities.
     *
     * @Route("/", name="crud_product")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\Product')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Shop\Product entity.
     *
     * @Route("/{id}/show", name="crud_product_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Shop\Product entity.
     *
     * @Route("/new", name="crud_product_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Product();
        $form   = $this->createForm(new ProductType( $this->getDoctrine()->getEntityManager() ), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Shop\Product entity.
     *
     * @Route("/create", name="crud_product_create")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\Product:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Product();
        $request = $this->getRequest();
        $form    = $this->createForm(new ProductType($this->getDoctrine()->getEntityManager()), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_product_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\Product entity.
     *
     * @Route("/{id}/edit", name="crud_product_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Product entity.');
        }

        $editForm = $this->createForm(new ProductType($this->getDoctrine()->getEntityManager()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shop\Product entity.
     *
     * @Route("/{id}/update", name="crud_product_update")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\Product:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Product entity.');
        }

        $editForm   = $this->createForm(new ProductType($this->getDoctrine()->getEntityManager()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_product_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shop\Product entity.
     *
     * @Route("/{id}/delete", name="crud_product_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Shop\Product')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\Product entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_product'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
