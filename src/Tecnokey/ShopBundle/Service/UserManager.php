<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserManager
 *
 * @author mqmtech
 */
namespace Tecnokey\ShopBundle\Service;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Tecnokey\ShopBundle\Entity\Shop\User;

class UserManager {
    
    /**
     *
     * @var SecurityContextInterface
     */
    private $securityContext;
    
    /**
     *
     * @var RegistryInterface 
     */
    private $doctrine;
    
    public function __construct(RegistryInterface $doctrine, SecurityContextInterface $securityContext) {

        $this->doctrine = $doctrine;
        $this->securityContext = $securityContext;
    }
    
    /**
     *
     * @return User
     */
    public function getCurrentUser(){
        if($this->securityContext != NULL){
            $user = $this->securityContext->getToken()->getUser(); 
            return $user;
        }
        else{
            throw new \Exception("Custom Exception: No SecurityContext has been setted in UserManager");
        }
    }  
    
    public function isDBUser($user){
        
        $isDBUser = false;
        
        if($user == NULL || gettype($user) == "string"){
            $isDBUser = false;
        }
        else{
            if(method_exists($user, "isDBUser")){
                $isDBUser = $user->isDBUser();
            }
            else{
                $isDBUser = false;
            }
        }
        
        return $isDBUser;
    }
}

?>
