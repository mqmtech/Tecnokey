<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\StatBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Description of StatManager
 *
 * @author mqmtech
 */
class StatManager {
    
    const TYPE_PRODUCT_VIEW_STAT= "stat_product_view";
    const TYPE_CATEGORY_VIEW_STAT= "stat_category_view";
    
    private $doctrine;
    
    /**
     *
     * @param type $doctrine 
     */
    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
    }
    
    /**
     * @param Statistic $stat
     * @param Entry $entry
     */
    public function registerEntry($statistic, $entry){
        $entry->setStatistic($statistic);
    }
    
    public function getStatisticByName($statName){
        $em = $this->getEntityManager();
        
        $entity = null;
        if($em != null){
           $entity = $em->getRepository('MQMTechStatBundle:Statistic')->findBy(array('name' => $statName));
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Statistic entity.');
        }
        
        return $entity;
    }
    
    public function getCountEntries($statistic, $entryType, $entryId){
        $em = $this->getEntityManager();
        
        $entity = null;
        if($em != null){
           $entity = $em->getRepository('MQMTechStatBundle:Statistic')->findCountEntries($statName, $entryType, $entryId);
        }
    }
    
    
    /**
     *
     * @return Registry
     */
    private function getEntityManager(){
        if($this->doctrine != NULL){
            return $this->doctrine->getEntityManager();            
        }
        else{
            throw new \Exception("Custom Exception: No DatabaseManager has been setted in ShoppingCartManager");
        }
    }
}

?>
