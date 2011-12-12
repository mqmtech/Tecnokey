<?php
namespace Tecnokey\ShopBundle\Repository;
use Doctrine\ORM\EntityRepository;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryDAO
 *
 * @author MQMTECH
 */
class UserRepository extends EntityRepository{
    
    const RECENT_MAX_RESULTS = 10;
    const RECENT_ORDER_BY = 'DESC';
    
    const USER_PERMISSION_TYPE = 'ROLE_USER';
    
    
    public function findRecentClients($max = self::RECENT_MAX_RESULTS){

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
