<?php

namespace Tecnokey\ShopBundle\Controller\Frontend\User;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Form\Shop\UserType;
use Tecnokey\ShopBundle\Entity\Shop\User;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/registro")
 */
class RegisterController extends Controller {

    /**
     *
     * @Route("/new.{_format}", defaults={"_format"="html"}, name="TKShopFrontendUserNew")
     * @Template()
     */
    public function newAction($_format) {

        //return $this->render("TecnokeyShopBundle:Frontend\User\Default:register.".$_format.".twig", array('lastUser' => "admin"));
        $entity = new User(); //modded

        $form = $this->createForm(new UserType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new Shop\User entity.
     *
     * @Route("/test", name="TKShopFrontendUserTest")
     * @Method("get")
     * @Template("TecnokeyShopBundle:Frontend\User\Register:successMessageOnCreate.html.twig")
     */
    public function testAction() {
        return array();
    }

    /**
     * Creates a new Shop\User entity.
     *
     * @Route("/create", name="TKShopFrontendUserCreate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Frontend\User\Register:new.html.twig")
     */
    public function createAction() {
        $entity = new User(); //modded

        $request = $this->getRequest();
        $form = $this->createForm(new UserType(), $entity);
        $form->bindRequest($request);


        if ($form->isValid() && $this->extraRegistrationValidation($entity)) {
            // encode password //
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            // end encoding password //
            //copy email to username if username is 
            if ($entity->getUsername() == null || $entity->getUsername() == "") {
                $entity->setUsername($entity->getEmail());
            }

            try {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();
                //return $this->redirect($this->generateUrl('TKShopFrontendUserShow', array('id' => $entity->getId())));
                return $this->render("TecnokeyShopBundle:Frontend\User\Register:successMessageOnCreate." . "html" . ".twig", array('user' => $entity));
                
            } catch (Exception $e) {
                $this->get('session')->setFlash('Email', "El email empleado ya está registrado");
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\User entity.
     *
     * @Route("/{id}/edit", name="TKShopFrontendUserEdit")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
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
    public function updateAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
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

            return $this->redirect($this->generateUrl('TKShopFrontendUserEdit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    public function extraRegistrationValidation($entity) {
        
        $validation = true;
        
        //Validate mail and passwords
        if (!$this->validateEmail($entity)) {
            $this->get('session')->setFlash('Email', "No coincide en las dos casillas");
            $validation = false;
        }
        if (!$this->validatePassword($entity)) {
            $this->get('session')->setFlash('Contraseña', "No coincide en las dos casillas");
            $validation = false;
        }

        if (!$this->validateCheckbox()) {
            $this->get('session')->setFlash('Política de uso', "Debe aceptar lo política de uso de Tecnokey");
            $validation = false;
        }
        //End validating mail and passwords
        
        return $validation;
    }

    private function validateCheckbox() {
        $request = Request::createFromGlobals();
        $hasCheckbox = $request->request->has('checkbox');

        return $hasCheckbox;
    }

    private function validateEmail(User $entity) {
        $request = Request::createFromGlobals();
        $email = $request->request->get('emailConfirmation');

        if ($entity->getEmail() != $email) {
            return false;
        }

        return true;
    }

    private function validatePassword(User $entity) {
        $request = Request::createFromGlobals();
        $password = $request->request->get('passwordConfirmation');

        if ($entity->getPassword() != $password) {
            return false;
        }
        return true;
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Frontend demo
     *
     * @Route("/auto_login.{_format}", defaults={"_format"="html"}, name="TKShopFrontendUserAutoLogin")
     */
    public function autoLoginAction() {

        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository("TecnokeyShopBundle:Shop\User")->find(1);

        // create the authentication token
        $token = new UsernamePasswordToken(
                        $user,
                        null,
                        'main',
                        $user->getRoles());
        // give it to the security context
        $this->container->get('security.context')->setToken($token);

        return $this->redirect($this->generateUrl("TKShopFrontendIndex"));
    }

}
