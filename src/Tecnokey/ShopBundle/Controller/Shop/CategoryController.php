<?php

namespace Tecnokey\ShopBundle\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Category;
use Tecnokey\ShopBundle\Form\Shop\CategoryType;

/**
 * Shop\Category controller.
 *
 * @Route("/crud/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Shop\Category entities.
     *
     * @Route("/", name="crud_category")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Shop\Category entity.
     *
     * @Route("/{id}/show", name="crud_category_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Shop\Category entity.
     *
     * @Route("/new", name="crud_category_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Category();
        $form   = $this->createForm(new CategoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Shop\Category entity.
     *
     * @Route("/create", name="crud_category_create")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\Category:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Category();
        $request = $this->getRequest();
        $form    = $this->createForm(new CategoryType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_category_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\Category entity.
     *
     * @Route("/{id}/edit", name="crud_category_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Category entity.');
        }

        $editForm = $this->createForm(new CategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shop\Category entity.
     *
     * @Route("/{id}/update", name="crud_category_update")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\Category:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Category entity.');
        }

        $editForm   = $this->createForm(new CategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_category_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shop\Category entity.
     *
     * @Route("/{id}/delete", name="crud_category_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\Category entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_category'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
