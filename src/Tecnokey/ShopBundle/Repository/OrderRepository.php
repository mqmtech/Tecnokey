<?php
namespace Tecnokey\ShopBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Tecnokey\ShopBundle\Entity\Shop\Order;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BrandDAO
 *
 * @author MQMTECH
 */
class OrderRepository extends EntityRepository{
    
    /**
     * 
     * return array
     */
    public function findDelivered(){

        //Grab categories from db
        $em = $this->getEntityManager();
        $q = $em->createQuery("select o from TecnokeyShopBundle:Shop\\Order o WHERE o.status LIKE '" . Order::STATUS_2_DELIVERED . "'");
        $orders = $q->getResult();
        //End grabbing cats from db
        
        if($orders == NULL){
            return NULL;
        }
        
        return $orders;
    }
    
        /**
     * 
     * return array
     */
    public function findInProcess(){

        //Grab categories from db
        $em = $this->getEntityManager();
        $q = $em->createQuery("select o from TecnokeyShopBundle:Shop\\Order o WHERE o.status <> '" . Order::STATUS_2_DELIVERED . "'");
        $orders = $q->getResult();
        //End grabbing cats from db
        
        if($orders == NULL){
            return NULL;
        }
        
        return $orders;
    }

}

?>
