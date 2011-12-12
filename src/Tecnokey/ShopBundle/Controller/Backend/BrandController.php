<?php

namespace Tecnokey\ShopBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Brand;
use Tecnokey\ShopBundle\Form\Shop\BrandType;
use Doctrine\Common\Collections\ArrayCollection;

use MQMTech\ToolsBundle\Service\Pagination;
/**
 * Shop\Brand controller.
 *
 * @Route("/admin/marcas")
 */
class BrandController extends Controller
{
    /**
     * Lists all Shop\Brand entities.
     *
     * @Route("/", name="TKShopBackendBrandsIndex")
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
     * @Route("/{id}/ver", name="TKShopBackendBrandShow")
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
     *
     * @Route("/administracion", name="TKShopBackendBrandsShowAll")
     * @Template()
     */
    public function showAllAction() {
        //Grab products from db
        $em = $this->getDoctrine()->getEntityManager();
        $brands = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->findAll();
        //End grabbing prods from db
        
        //Set Pagination
        $pagination = NULL;
        if($brands != NULL){
            $totalItemsLength = count($brands);
            $pagination = $this->get('view.pagination')->calcPagination($totalItemsLength); 
            $brands = $pagination->sliceArray($brands);
        }
        //End Setting Pagination
        
        //set the delete form for every product
        $deleteForms = array();
        foreach ($brands as $brand) {
            $form = $this->createDeleteForm($brand->getId());
            $deleteForms[$brand->getId()] = $form->createView();
        }
        //end setting the delete form for every product
        
        return array(
            'brands' => $brands,
            'deleteForms' => $deleteForms,
            'pagination' => $pagination,
        );      
    }

    /**
     * Displays a form to create a new Shop\Brand entity.
     *
     * @Route("/~/nuevo", name="TKShopBackendBrandNew")
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
     * @Route("/create", name="TKShopBackendBrandCreate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Backend\Brand:new.html.twig")
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

            return $this->redirect($this->generateUrl('TKShopBackendBrandsShowAll'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\Brand entity.
     *
     * @Route("/{id}/editar", name="TKShopBackendBrandEdit")
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
     * @Route("/{id}/actualizar", name="TKShopBackendBrandUpdate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Backend\Brand:edit.html.twig")
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
            //FIX VIRTUAL file update in Image
            $image = $entity->getImage();
            if($image != null){
                if($image->isFileUpdated() == false){
                    $image->setFileUpdated(true);
                }
                else{
                    $image->setFileUpdated(false);
                }   
            }
            //END FIX VIRTUAL file update in Image 
        
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('TKShopBackendBrandsShowAll'));
        }
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
        /**
     * Displays a form to edit an existing Shop\Brand entity.
     *
     * @Route("/{id}/clonar", name="TKShopBackendBrandClone")
     * @Template("TecnokeyShopBundle:Backend\Brand:new.html.twig")
     */
    public function cloneAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Brand')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Brand entity.');
        }
        
        $entityCloned = clone ($entity);

        $editForm = $this->createForm(new BrandType(), $entityCloned);

        return array(
            'entity'      => $entityCloned,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a Shop\Brand entity.
     *
     * @Route("/{id}/delete", name="TKShopBackendBrandDelete")
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
            try{
                $em->remove($entity);
                $em->flush();
            }
            catch(\Exception $e){
                $this->get('session')->setFlash('category_error',"Atencion: La MARCA no puede ser eliminada, eliminela de los productos previamente");
            }
        }

        return $this->redirect($this->generateUrl('TKShopBackendBrandsShowAll'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
