<?php

namespace Tecnokey\ShopBundle\Controller\Statistic;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Statistic\ProductStatistic;
use Tecnokey\ShopBundle\Form\Statistic\ProductStatisticType;

/**
 * Statistic\ProductStatistic controller.
 *
 * @Route("/crud/productstatistic")
 */
class ProductStatisticController extends Controller
{
    /**
     * Lists all Statistic\ProductStatistic entities.
     *
     * @Route("/", name="crud_productstatistic")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Statistic\ProductStatistic')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Statistic\ProductStatistic entity.
     *
     * @Route("/{id}/show", name="crud_productstatistic_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Statistic\ProductStatistic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistic\ProductStatistic entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Statistic\ProductStatistic entity.
     *
     * @Route("/new", name="crud_productstatistic_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ProductStatistic();
        $form   = $this->createForm(new ProductStatisticType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Statistic\ProductStatistic entity.
     *
     * @Route("/create", name="crud_productstatistic_create")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Statistic\ProductStatistic:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new ProductStatistic();
        $request = $this->getRequest();
        $form    = $this->createForm(new ProductStatisticType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_productstatistic_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Statistic\ProductStatistic entity.
     *
     * @Route("/{id}/edit", name="crud_productstatistic_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Statistic\ProductStatistic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistic\ProductStatistic entity.');
        }

        $editForm = $this->createForm(new ProductStatisticType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Statistic\ProductStatistic entity.
     *
     * @Route("/{id}/update", name="crud_productstatistic_update")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Statistic\ProductStatistic:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Statistic\ProductStatistic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistic\ProductStatistic entity.');
        }

        $editForm   = $this->createForm(new ProductStatisticType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_productstatistic_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Statistic\ProductStatistic entity.
     *
     * @Route("/{id}/delete", name="crud_productstatistic_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Statistic\ProductStatistic')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Statistic\ProductStatistic entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_productstatistic'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
