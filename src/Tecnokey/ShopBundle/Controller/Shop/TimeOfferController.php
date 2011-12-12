<?php

namespace Tecnokey\ShopBundle\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\TimeOffer;
use Tecnokey\ShopBundle\Form\Shop\TimeOfferType;

/**
 * Shop\TimeOffer controller.
 *
 * @Route("/crud/timeoffer")
 */
class TimeOfferController extends Controller
{
    /**
     * Lists all Shop\TimeOffer entities.
     *
     * @Route("/", name="crud_timeoffer")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\TimeOffer')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Shop\TimeOffer entity.
     *
     * @Route("/{id}/show", name="crud_timeoffer_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\TimeOffer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\TimeOffer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Shop\TimeOffer entity.
     *
     * @Route("/new", name="crud_timeoffer_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TimeOffer();
        $form   = $this->createForm(new TimeOfferType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Shop\TimeOffer entity.
     *
     * @Route("/create", name="crud_timeoffer_create")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\TimeOffer:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new TimeOffer();
        $request = $this->getRequest();
        $form    = $this->createForm(new TimeOfferType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_timeoffer_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\TimeOffer entity.
     *
     * @Route("/{id}/edit", name="crud_timeoffer_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\TimeOffer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\TimeOffer entity.');
        }

        $editForm = $this->createForm(new TimeOfferType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shop\TimeOffer entity.
     *
     * @Route("/{id}/update", name="crud_timeoffer_update")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\TimeOffer:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\TimeOffer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\TimeOffer entity.');
        }

        $editForm   = $this->createForm(new TimeOfferType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_timeoffer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shop\TimeOffer entity.
     *
     * @Route("/{id}/delete", name="crud_timeoffer_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Shop\TimeOffer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\TimeOffer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_timeoffer'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
