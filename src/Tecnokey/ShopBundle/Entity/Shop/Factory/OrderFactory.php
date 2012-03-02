<?php

namespace Tecnokey\ShopBundle\Entity\Shop\Factory;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Tecnokey\ShopBundle\Entity\Shop\Order;
use Tecnokey\ShopBundle\Service\UserManager;
use Tecnokey\ShopBundle\Entity\Shop\Factory\IOrderFactory;
use \DateTime;

class OrderFactory implements IOrderFactory{

    const UID_PADDING = 99;
    
    /**
     *
     * @var Request
     */
    private $userManager;
    
    public function __construct(UserManager $userManager) {
        $this->userManager = $userManager;
    }
    
    public function create(){
        $order = new Order();
        
        $publicId = $this->generateCode();
        $order->setPublicId($publicId);
        
        return $order;
    }
    
    public function generateCode(){
        $stamp = strtotime("now");
        $stamp = str_replace(".", "", $stamp);
        
        $userManager = $this->getUserManager();
        $user = $userManager->getCurrentUser();
        $userId = $user->getId() + self::UID_PADDING;
        
        //$username = $user->getUsername();
        //$username = substr($username,0, 4); 
        
        $code = $userId ."t". $stamp;
        
        return $code;
    }
    

    //**** Getters and Setters ****/
    /**
     *
     * @return UserManager
     */
    private function getUserManager() {
        return $this->userManager;
    }
    
}

?>
