<?php

namespace MQMTech\StatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MQMTech\StatBundle\Entity\Entry;
use MQMTech\StatBundle\Form\EntryType;

/**
 * Entry controller.
 *
 * @Route("/stats/crud/entry")
 */
class EntryController extends Controller
{
    /**
     * Lists all Entry entities.
     *
     * @Route("/", name="stats_crud_entry")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MQMTechStatBundle:Entry')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Entry entity.
     *
     * @Route("/{id}/show", name="stats_crud_entry_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MQMTechStatBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Entry entity.
     *
     * @Route("/new", name="stats_crud_entry_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Entry();
        $form   = $this->createForm(new EntryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Entry entity.
     *
     * @Route("/create", name="stats_crud_entry_create")
     * @Method("post")
     * @Template("MQMTechStatBundle:Entry:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Entry();
        $request = $this->getRequest();
        $form    = $this->createForm(new EntryType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stats_crud_entry_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Entry entity.
     *
     * @Route("/{id}/edit", name="stats_crud_entry_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MQMTechStatBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm = $this->createForm(new EntryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Entry entity.
     *
     * @Route("/{id}/update", name="stats_crud_entry_update")
     * @Method("post")
     * @Template("MQMTechStatBundle:Entry:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MQMTechStatBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm   = $this->createForm(new EntryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stats_crud_entry_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Entry entity.
     *
     * @Route("/{id}/delete", name="stats_crud_entry_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MQMTechStatBundle:Entry')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Entry entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stats_crud_entry'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
