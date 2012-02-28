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

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/usuario")
 */
class DefaultController extends Controller {

    /**
     * Frontend demo
     *
     * @Route("/", name="TKShopFrontendUserIndex")
     * @Template()
     */
    public function indexAction() {
        return $this->redirect($this->generateUrl('TKShopBackendPanelControlIndex'));        
    }
    
    /**
     * Frontend demo
     *
     * @Route("/ver.{_format}", defaults={"_format"="partialhtml"}, name="TKShopFrontendUserShow")
     * @Template()
     */
    public function showAction($_format){
        
        return $this->render("TecnokeyShopBundle:Frontend\User\Default:show.".$_format.".twig", array('lastUser' => "admin"));
    }  
    
    /**
     *
     * @Route("/new.{_format}", defaults={"_format"="html"}, name="TKShopFrontendUserNew")
     * @Template()
     */
    public function newAction($_format){
        
        //return $this->render("TecnokeyShopBundle:Frontend\User\Default:register.".$_format.".twig", array('lastUser' => "admin"));
        
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
     * @Route("/create", name="TKShopFrontendUserCreate")
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

            return $this->redirect($this->generateUrl('TKShopFrontendUserEdit', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\User entity.
     *
     * @Route("/{id}/edit", name="TKShopFrontendUserEdit")
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

            return $this->redirect($this->generateUrl('TKShopFrontendUserEdit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
     /**
     * Frontend demo
     *
     * @Route("/auto_login.{_format}", defaults={"_format"="html"}, name="TKShopFrontendUserAutoLogin")
     */
    public function autoLoginAction(){
        
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
