<?php

namespace Tecnokey\ShopBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Category;
use Tecnokey\ShopBundle\Form\Shop\CategoryType;
use Doctrine\Common\Collections\ArrayCollection;

use MQMTech\ToolsBundle\Service\Pagination;
/**
 * Shop\Category controller.
 *
 * @Route("/admin/categorias")
 */
class CategoryController extends Controller
{
    
    /**
     * Lists all Shop\Category entities.
     *
     * @Route("/", name="TKShopBackendCategoriesIndex")
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
     * @Route("/{id}/ver", name="TKShopBackendCategoryShow")
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
     * @Route("/~/nuevo", name="TKShopBackendCategoryNew")
     * @Template()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = new Category();
        
        $categoryType = $this->get('form.type.category');
        $form = $this->createForm($categoryType, $entity);
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Shop\Category entity.
     *
     * @Route("/create", name="TKShopBackendCategoryCreate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Backend\Category:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $entity  = new Category();
        $request = $this->getRequest();
        $categoryType = $this->get('form.type.category');
        $form    = $this->createForm($categoryType, $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('TKShopBackendCategoriesShowAll'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Shop\Category entity.
     *
     * @Route("/{id}/editar", name="TKShopBackendCategoryEdit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Category entity.');
        }

        $categoryType = $this->get('form.type.category');
        $editForm = $this->createForm($categoryType, $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Displays a form to edit an existing Shop\Category entity.
     *
     * @Route("/{id}/clonar", name="TKShopBackendCategoryClone")
     * @Template("TecnokeyShopBundle:Backend\Category:new.html.twig")
     */
    public function cloneAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Category entity.');
        }
        
        $entityCloned = clone ($entity);

        $categoryType = $this->get('form.type.category');
        $editForm = $this->createForm($categoryType, $entityCloned);

        return array(
            'entity'      => $entityCloned,
            'form'   => $editForm->createView(),
        );
        
    }

    /**
     * Edits an existing Shop\Category entity.
     *
     * @Route("/{id}/actualizar", name="TKShopBackendCategoryUpdate")
     * @Method("post")
     * @Template("TecnokeyShopBundle:Backend\Category:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop\Category entity.');
        }

        $categoryType = $this->get('form.type.category');
        $editForm   = $this->createForm($categoryType, $entity);
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
            else{
                throw new \Exception("Custom Exception: Image is NULL in the updateAction");
            }
            //END FIX VIRTUAL file update in Image 
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('TKShopBackendCategoriesShowAll'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
     /**
     *
     * @Route("/administracion", name="TKShopBackendCategoriesShowAll")
     * @Template()
     */
    public function showAllAction() {
        //Grab products from db
        $em = $this->getDoctrine()->getEntityManager();
        //$categories = $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAll();
        $categories = $em->getRepository('TecnokeyShopBundle:Shop\Category')->findAllFamilies();
        //End grabbing prods from db
        
        //Set Pagination
        $pagination = NULL;
        if($categories != NULL){
            $totalItemsLength = count($categories);
            $pagination = $this->get('view.pagination')->calcPagination($totalItemsLength); 
            $categories = $pagination->sliceArray($categories);
        }
        //End Setting Pagination
        
        //set the delete form for every product
        $deleteForms = array();
        foreach ($categories as $category) {
            $form = $this->createDeleteForm($category->getId());
            $deleteForms[$category->getId()] = $form->createView();
        }

        //end setting the delete form for every product
        
        return array(
            'categories' => $categories,
            'deleteForms' => $deleteForms,
            'pagination' => $pagination,
        );      
    }
    
     /**
     *
     * @Route("/administracion/subcategoria/{id}", name="TKShopBackendCategoriesShowAllSubcategories")
     * @Template("TecnokeyShopBundle:Backend\Category:showAll.html.twig")
     */
    public function showAllSubCategoriesAction($id) {
        //Grab cats
        $em = $this->getDoctrine()->getEntityManager();
        $category = $em->getRepository('TecnokeyShopBundle:Shop\Category')->find($id);
        $categories = $category->getCategories();
        //End grabbing cats
        
        //Set Pagination
        $pagination = NULL;
        if($categories != NULL){
            $totalItemsLength = count($categories);
            $pagination = $this->get('view.pagination')->calcPagination($totalItemsLength); 
            $categories = $pagination->sliceArray($categories);
        }
        //End Setting Pagination
        
        //set the delete form for every product
        $deleteForms = array();
        foreach ($categories as $subCategory) {
            $form = $this->createDeleteForm($subCategory->getId());
            $deleteForms[$subCategory->getId()] = $form->createView();
        }
        //end setting the delete form for every product
        
        return array(
            'category' => $category,
            'categories' => $categories,
            'deleteForms' => $deleteForms,
            'pagination' => $pagination,
        );      
    }

    /**
     * Deletes a Shop\Category entity.
     *
     * @Route("/{id}/delete", name="TKShopBackendCategoryDelete")
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
            
            try {
                $em->remove($entity);
                $em->flush();
            }
            catch(\Exception $e){
                 $this->get('session')->setFlash('category_error',"Atencion: La categoria no puede ser eliminada, elimine las subcategorias y productos asociados previamente");
            }
        }

        return $this->redirect($this->generateUrl('TKShopBackendCategoriesShowAll'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
