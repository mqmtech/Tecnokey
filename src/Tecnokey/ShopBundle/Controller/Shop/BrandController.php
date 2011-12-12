<?php

namespace Tecnokey\ShopBundle\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Brand;
use Tecnokey\ShopBundle\Form\Shop\BrandType;

/**
 * Shop\Brand controller.
 *
 * @Route("/crud/brand")
 */
class BrandController extends Controller
{
    /**
     * Lists all Shop\Brand entities.
     *
     * @Route("/", name="crud_brand")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Shop\Brand entity.
     *
     * @Route("/{id}/show", name="crud_brand_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Brand entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Shop\Brand entity.
     *
     * @Route("/new", name="crud_brand_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Brand();
        $form   = $this->createForm(new BrandType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Shop\Brand entity.
     *
     * @Route("/create", name="crud_brand_create")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\Brand:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Brand();
        $request = $this->getRequest();
        $form    = $this->createForm(new BrandType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_brand_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\Brand entity.
     *
     * @Route("/{id}/edit", name="crud_brand_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Brand entity.');
        }

        $editForm = $this->createForm(new BrandType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shop\Brand entity.
     *
     * @Route("/{id}/update", name="crud_brand_update")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\Brand:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Brand entity.');
        }

        $editForm   = $this->createForm(new BrandType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_brand_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shop\Brand entity.
     *
     * @Route("/{id}/delete", name="crud_brand_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\Brand entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_brand'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
