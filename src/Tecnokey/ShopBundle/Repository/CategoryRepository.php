<?php
namespace Tecnokey\ShopBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryDAO
 *
 * @author MQMTECH
 */
class CategoryRepository extends EntityRepository{
    
    const MAX_RANDOM_RESULTS = 4;
    
     /**
     * list of categories
     * 
     * return array
     */
    public function findAllFamilies(){

        //Grab categories from db
        $em = $this->getEntityManager();
        $q = $em->createQuery('select cat from TecnokeyShopBundle:Shop\\Category cat WHERE cat.parentCategory is NULL ORDER BY cat.name ASC');
        $categories = $q->getResult();
        //End grabbing cats from db

        return $categories;
    }
    
    /**
     * Random list of categories
     * 
     * return array
     */
    public function findRandomFamilies($maxCount = self::MAX_RANDOM_RESULTS){

        //Grab categories from db
        $em = $this->getEntityManager();
        $q = $em->createQuery('select cat from TecnokeyShopBundle:Shop\\Category cat WHERE cat.parentCategory is NULL');
        $categories = $q->getResult();
        //End grabbing cats from db
        
        if($categories == NULL){
            return NULL;
        }
        
        $catSize = sizeof($categories);
        if($catSize < $maxCount){
            $maxCount = $catSize;
        }
        $rand_keys = array_rand($categories, $maxCount);
        
        $randCategories = array();
        
        if(sizeof($rand_keys) > 1){
            foreach ($rand_keys as $key) {
                 $randCategories[] = $categories[$key];            
            }
        }
        else{
            $randCategories[] = $categories[$rand_keys];            
        }
        
        return $randCategories;
    }

}

?>
