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

    public function findByPublicId($publicId){
        $em = $this->getEntityManager();
        
        $sql = "select o from TecnokeyShopBundle:Shop\\Order o WHERE o.publicId = '".$publicId."' ";
        $q = $em->createQuery($sql);
        $entity = $q->getSingleResult();
        
        return $entity;
    }
    
}

?>
