<?php
namespace Tecnokey\ShopBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BrandDAO
 *
 * @author MQMTECH
 */
class BrandRepository extends EntityRepository{
    
    const MAX_RANDOM_RESULTS = 10;
    
    /**
     * Random list of categories
     * 
     * return array
     */
    public function findRandom($maxCount = self::MAX_RANDOM_RESULTS){

        //Grab categories from db
        $em = $this->getEntityManager();
        $q = $em->createQuery('select b from TecnokeyShopBundle:Shop\\Brand b');
        $brands = $q->getResult();
        //End grabbing cats from db
        
        if($brands == NULL){
            return NULL;
        }
        
        $size = sizeof($brands);
        if($size < $maxCount){
            $maxCount = $size;
        }
        $rand_keys = array_rand($brands, $maxCount);
        
        $randBrands = array();
        
        if(sizeof($rand_keys) > 1){
            foreach ($rand_keys as $key) {
                 $randBrands[] = $brands[$key];            
            }
        }
        else{
            $randBrands[] = $brands[$rand_keys];            
        }
        
        return $randBrands;
    }

}

?>
