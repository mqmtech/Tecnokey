<?php

namespace MQMTech\StatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MQMTech\StatBundle\Entity\Statistic;
use MQMTech\StatBundle\Form\StatisticType;

/**
 * Statistic controller.
 *
 * @Route("/stats/crud/stat")
 */
class StatisticController extends Controller
{
    /**
     * Lists all Statistic entities.
     *
     * @Route("/", name="stats_crud_stat")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MQMTechStatBundle:Statistic')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Statistic entity.
     *
     * @Route("/{id}/show", name="stats_crud_stat_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MQMTechStatBundle:Statistic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistic entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Statistic entity.
     *
     * @Route("/new", name="stats_crud_stat_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Statistic();
        $form   = $this->createForm(new StatisticType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Statistic entity.
     *
     * @Route("/create", name="stats_crud_stat_create")
     * @Method("post")
     * @Template("MQMTechStatBundle:Statistic:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Statistic();
        $request = $this->getRequest();
        $form    = $this->createForm(new StatisticType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stats_crud_stat_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Statistic entity.
     *
     * @Route("/{id}/edit", name="stats_crud_stat_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MQMTechStatBundle:Statistic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistic entity.');
        }

        $editForm = $this->createForm(new StatisticType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Statistic entity.
     *
     * @Route("/{id}/update", name="stats_crud_stat_update")
     * @Method("post")
     * @Template("MQMTechStatBundle:Statistic:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MQMTechStatBundle:Statistic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistic entity.');
        }

        $editForm   = $this->createForm(new StatisticType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stats_crud_stat_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Statistic entity.
     *
     * @Route("/{id}/delete", name="stats_crud_stat_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MQMTechStatBundle:Statistic')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Statistic entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stats_crud_stat'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
