<?php

namespace Tecnokey\ShopBundle\Controller\Shop;

use Tecnokey\ShopBundle\Entity\Shop\Brand;

use Tecnokey\ShopBundle\Entity\Shop\Product;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints\Image;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Category;
use Tecnokey\ShopBundle\Form\Shop\CategoryType;
use Exception;

/**
 * Shop\Category controller.
 *
 * @Route("/test")
 */
class ShopTestController extends Controller {
	
	/**
	 *
	 * @Route("/create_product_brand", name = "shop_create_product_brand")
	 * 
	 * Tested Ok
	 */
	public function testCreateProductBrandAction() {
		$output = "output testCreateProductCatAction<br/>";
		
		$em = $this->getDoctrine ()->getEntityManager ();
		
		//Get/Create Brand
		$brandName = "Adidas";
		$q = $em->createQuery("select brand from TecnokeyShopBundle:Shop\\Brand brand WHERE brand.name = '".$brandName."' ");
		try {
			$brand = $q->getSingleResult();
		}
		catch (Exception $e) {
			$output.= "<br/> WARNING: There is no brand with name ".$brandName." found";
			$brand = new Brand();
			$brand->setName($brandName);
			$output.= "<br/> INFO: Brand with name ".$brand->getName(). " created";	
		}
		//End Create Brand
		
		//Get/Create Product
		$prodName = "Llave normal";
		$q = $em->createQuery("select p from TecnokeyShopBundle:Shop\\Product p WHERE p.name = '".$prodName."' ");
		try {
			$product = $q->getSingleResult();
		} catch (Exception $e) {
			$output.= "<br/> WARNING: There is no product with name ".$prodName. " found";
			$product = new Product();
			$product->setName($prodName);
			$output.= "<br/> INFO: Product with name ".$product->getName(). " created";
		}
		//End Create Product
		
		//Get/add brand into product
		if($product->getBrand() == NULL){
			$output.= "<br/> INFO: No Brand in product ".$product->getName();
			$output.= "<br/> INFO: Adding brand ".$brand->getName(). " to ". $product->getName();
			$product->setBrand($brand);
			/*$output.= "<br/> INFO: New Brand ". $brand->getName() ." added to product ". $product->getName();*/
		}
		
		//Testing the product with its categories
		$q = $em->createQuery("select p from TecnokeyShopBundle:Shop\\Product p WHERE p.name = '".$prodName."' ");
		try {
			$productGrabbed = $q->getSingleResult();
			$brandGrabbed = $productGrabbed->getBrand();
			$output.="<br/>INFO: Brand inside product: ". $productGrabbed->getName(). ": " . $brandGrabbed->getName();
		} catch (Exception $e) {
			$output.="<br/>FAILURE: There's no product with name: ".$prodName;
		}
		//END Testing the product with its categories
		
		$em->persist ( $product ); //Persist category
		$em->flush ();
		return new Response ( $output );
	}
	
	/**
	 * Displays a form to edit an existing Shop\Category entity.
	 *
	 * @Route("/create_cat_cat", name="shop_test_create")
	 */
	public function testCreateCatCatAction() {
		$output = "output testCreateCatCatAction";
		
		$em = $this->getDoctrine ()->getEntityManager ();
		
		//Get/Create TOP Category
		$catName = "Top Category";
		$q = $em->createQuery("select cat from TecnokeyShopBundle:Shop\Category cat WHERE cat.name = '".$catName."' ");
		try {
			$topCategory = $q->getSingleResult();
		}
		catch (Exception $e) {
			$output.= "<br/> WARNING: There is no category with name ".$catName." found";
			$topCategory = new Category();
			$topCategory->setName($catName);
			$output.= "<br/> INFO: Category with name ".$topCategory->getName(). " created";	
		}
		//Emd Get/Create TOP Category
		
		//Get/Create SUB1 Category
		$catName = "Sub1 Category";
		$q = $em->createQuery("select cat from TecnokeyShopBundle:Shop\Category cat WHERE cat.name = '".$catName."' ");
		try {
			$sub1Category = $q->getSingleResult();
		}
		catch (Exception $e) {
			$output.= "<br/> WARNING: There is no category with name ".$catName." found";
			$sub1Category = new Category();
			$sub1Category->setName($catName);
			$output.= "<br/> INFO: Category with name ".$sub1Category->getName(). " created";	
		}
		//Emd Get/Create SUB1 Category
		
		//Get/Create SUB2 Category
		$catName = "Sub2 Category";
		$q = $em->createQuery("select cat from TecnokeyShopBundle:Shop\Category cat WHERE cat.name = '".$catName."' ");
		try {
			$sub2Category = $q->getSingleResult();
		}
		catch (Exception $e) {
			$output.= "<br/> WARNING: There is no category with name ".$catName." found";
			$sub2Category = new Category();
			$sub2Category->setName($catName);
			$output.= "<br/> INFO: Category with name ".$sub2Category->getName(). " created";	
		}
		//Emd Get/Create SUB1 Category
		
		//Set relationships
		if(sizeof($topCategory->getCategories()) < 1){
			$output.= "<br/> INFO: ADDING Parent Categories to Sub1 and Sub2 Categories";
		}
		if($sub1Category->getParentCategory() != $topCategory){
			$sub1Category->setParentCategory($topCategory);	
		}
		
		if($sub2Category->getParentCategory() != $topCategory){
			$sub2Category->setParentCategory($topCategory);	
		}
		
//		$topCategories = $topCategory->getCategories();
//		if ($topCategories == NULL) {
//			$output.= "<br/> WARNING: TopCategories Array is null when trying to add subCategories!";
//			$topCategories = array();
//		}
//		if(sizeof($topCategories) < 1){
//			$output.= "<br/> INFO: ADDING Categories to TopCategories Array directly";
//			$topCategories [] = $sub1Category;
//			$topCategories [] = $sub2Category;
//			
//			$topCategory->setCategories($topCategories);
//			$em->persist ( $topCategory );
//			$em->flush ();
//		}
		//End setting relationships
		
		//show subcategories
		$topCategories = $topCategory->getCategories();
		$output.="<br/> Showing subcategories from ".$topCategory->getName();
		
		/*if(sizeof($topCategories) == 0){
			
		}*/
		if($topCategories == NULL){
			$output.="<br/> ERROR: topCategories Array is NULL";
		}
		else{
			foreach ($topCategories as $subcategory) {
				$output.="<br/>- value: ".$subcategory->getName();
			}	
		}
		//End Showing subcats
		
		//show parentCats
		if($sub1Category->getParentCategory() != NULL){
			$parentCategory = $sub1Category->getParentCategory()->getName();
			$output.="<br/> Parent Category from ".$sub1Category->getName() . " is: ". $parentCategory;
		}
		
		if($sub1Category->getParentCategory() != NULL){
			$parentCategory = $sub2Category->getParentCategory()->getName();
			$output.="<br/> Parent Category from ".$sub2Category->getName() . " is: ". $parentCategory;
		}
		
		
		//End Showing parentCats

		//Save all to DataStore
		$em->persist ( $sub1Category );
		$em->persist ( $sub2Category );
		$em->persist ( $topCategory );
		$em->flush ();
		//End Saving all to DataStore
				
		//End Creating

		//Testing
		$catName = "Top Category";
		$q = $em->createQuery("select cat from TecnokeyShopBundle:Shop\Category cat WHERE cat.name = '".$catName."' ");
		$output.= "<br/> INFO: Testing subcategories from category: ".$catName;
		try {
			$grabbedCategory = $q->getSingleResult();
			$grabbedCategories = $grabbedCategory->getCategories();
			foreach ($grabbedCategories as $grabbedSubcategory) {
				$output.="<br/>- value: ".$grabbedSubcategory->getName();
			}
		}
		catch (Exception $e) {
			$output.= "<br/> WARNING: There is no category with name ".$catName." found";
				
		}
		
		$catName = "Sub1 Category";
		$q = $em->createQuery("select cat from TecnokeyShopBundle:Shop\Category cat WHERE cat.name = '".$catName."' ");
		$output.= "<br/> INFO: Testing Parent Categories from category: ".$catName;
		try {
			$grabbedCategory = $q->getSingleResult();
			
		if($grabbedCategory->getParentCategory() != NULL){
			$parentCategory = $grabbedCategory->getParentCategory()->getName();
			$output.="<br/> Parent Category from ".$grabbedCategory->getName() . " is: ". $parentCategory;
		}
			
		}
		catch (Exception $e) {
			$output.= "<br/> WARNING: There is no category with name ".$catName." found";
				
		}
		
		
		//End testing
		
		return new Response ( $output );
	}
	
	/**
	 *
	 * @Route("/create_product_cat", name = "shop_create_product_cat")
	 * 
	 * Tested Ok
	 */
	public function testCreateProductCatAction() {
		$output = "output testCreateProductCatAction<br/>";
		
		$em = $this->getDoctrine ()->getEntityManager ();
		
		//Get/Create Category
		$catName = "Llaves mecanicas";
		$q = $em->createQuery("select cat from TecnokeyShopBundle:Shop\Category cat WHERE cat.name = '".$catName."' ");
		try {
			$category = $q->getSingleResult();
		}
		catch (Exception $e) {
			$output.= "<br/> WARNING: There is no category with name ".$catName." found";
			$category = new Category();
			$category->setName("Llaves mecanicas");
			$output.= "<br/> INFO: Category with name ".$category->getName(). " created";	
		}
		//End Create Category
		
		//Get/Create Product
		$prodName = "Llave Allen";
		$q = $em->createQuery("select p from TecnokeyShopBundle:Shop\Product p WHERE p.name = '".$prodName."' ");
		try {
			$product = $q->getSingleResult();
		} catch (Exception $e) {
			$output.= "<br/> WARNING: There is no product with name ".$prodName. " found";
			$product = new Product();
			$product->setName("Llave Allen");
			$output.= "<br/> INFO: Product with name ".$product->getName(). " created";
		}
		
		//Get/add categories into product
		$categories = $product->getCategories();
		if(sizeof($categories) == 0){
			$output.= "<br/> INFO: EMPTY Category list in product ".$product->getName();
			$output.= "<br/> INFO: Adding category ".$category->getName(). " to ". $product->getName();
			$categories[] = $category;
			$product->setCategories($categories); // *** CHECK the need of having this line ***
			$output.= "<br/> INFO: New category ". $category->getName() ." added to product ". $product->getName();
		}
		
		////Show categories in product
//		$output.= "<br/> INFO: Showing categories in ". $product->getName() . "...:";
//		foreach ($categories as $aCategory){
//				$output.="<br/> INFO: category: ".$aCategory->getName();
//		}
		//End Create Product
		
		//Testing the product with its categories
		$q = $em->createQuery("select p from TecnokeyShopBundle:Shop\Product p WHERE p.name = '".$prodName."' ");
		try {
			$productGrabbed = $q->getSingleResult();
			$categoriesGrabbed = $productGrabbed->getCategories();
			$output.="<br/>INFO: Categories inside product: ". $productGrabbed->getName();
			foreach ($categoriesGrabbed as $categoryGrabbed){
				$output.="<br/>INFO: category: ".$categoryGrabbed->getName();
			}
			
		} catch (Exception $e) {
			$output.="<br/>FAILURE: There's no categories to show in ".$prodName;
		}
		//END Testing the product with its categories
		
		//Testing, show products inside one category
		$q = $em->createQuery("select cat from TecnokeyShopBundle:Shop\Category cat WHERE cat.name = '".$catName."' ");
		try {
			$categoryGrabbed= $q->getSingleResult();
			$productsGrabbed = $categoryGrabbed->getProducts();
			$output.="<br/>INFO: Products inside category: ". $categoryGrabbed->getName();
			foreach ($productsGrabbed as $productGrabbed){
				$output.="<br/>INFO: product: ".$productGrabbed->getName();
			}
			
		} catch (Exception $e) {
			$output.="<br/>FAILURE: There's no categories to show in ".$prodName;
		}
		//END Testing the product with its categories
		
		$em->persist ( $product ); //Persist category
		$em->flush ();
		return new Response ( $output );
	}
	
	/**
	 * Lists all Shop\Category entities.
	 *
	 * @Route("/", name="shop_test")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine ()->getEntityManager ();
		
		$entities = $em->getRepository ( 'TecnokeyShopBundle:Shop\Category' )->findAll ();
		
		return array ('entities' => $entities );
	}

}
