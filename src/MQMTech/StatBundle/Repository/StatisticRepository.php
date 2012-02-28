<?php
namespace MQMTech\StatBundle\Repository;
use Doctrine\ORM\EntityRepository;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author MQMTECH
 */
class StatisticRepository extends EntityRepository{
    
    public function findCountEntries($statName, $entryType, $entryId){

        $em = $this->getEntityManager();

        //Grab categories from db
        $em = $this->getEntityManager();
        $q = $em->createQuery("select user from TecnokeyShopBundle:Shop\\User user WHERE user.permissionType = '". self::USER_PERMISSION_TYPE ."' ORDER BY user.createdAt ".self::RECENT_ORDER_BY);
        $q->setMaxResults($max);
        $users = $q->getResult();

        return $users;
        //End grabbing cats from db
    }
}

?>
