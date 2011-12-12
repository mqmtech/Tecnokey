<?php

namespace Tecnokey\ShopBundle\Controller\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\User;
use Tecnokey\ShopBundle\Form\Shop\UserType;

/**
 * Shop\User controller.
 *
 * @Route("/crud/user")
 */
class UserController extends Controller
{
    /**
     * Lists all Shop\User entities.
     *
     * @Route("/", name="crud_user")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TecnokeyShopBundle:Shop\User')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Shop\User entity.
     *
     * @Route("/{id}/show", name="crud_user_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Shop\User entity.
     *
     * @Route("/new", name="crud_user_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createForm(new UserType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Shop\User entity.
     *
     * @Route("/create", name="crud_user_create")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\User:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new User();
        $request = $this->getRequest();
        $form    = $this->createForm(new UserType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            // encode password //
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            // end encoding password //
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_user_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\User entity.
     *
     * @Route("/{id}/edit", name="crud_user_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shop\User entity.
     *
     * @Route("/{id}/update", name="crud_user_update")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Shop\User:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\User entity.');
        }

        $editForm   = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            
            // encode password //
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            // end encoding password //
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shop\User entity.
     *
     * @Route("/{id}/delete", name="crud_user_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('TecnokeyShopBundle:Shop\User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop\User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_user'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
