<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Tecnokey\ShopBundle\Service;

use Tecnokey\ShopBundle\Entity\Shop\TimeOffer;
/**
 * Description of PriceInfo
 *
 * @author mqmtech
 */
class PriceInfo {

    /**
     *
     * @var float
     */
    private $basePrice;
    
    /**
     *
     * @var array
     */
    private $appliedOffers = array();
    
    public function getBasePrice() {
        return $this->basePrice;
    }

    public function setBasePrice($basePrice) {
        $this->basePrice = $basePrice;
    }

    /**
     *
     * @return TimeOffer
     */
    public function getAppliedOffers() {
        return $this->appliedOffers;
    }

    public function setAppliedOffers(array $appliedOffers) {
        $this->appliedOffers = $appliedOffers;
    }
    
    public function addAppliedOffer(TimeOffer $offer){
        $appliedOffers = $this->getAppliedOffers();
        $appliedOffers[] = $offer;
    }
    
    /**
     * Returns true if there's any offer, false otherwise
     * @return type 
     */
    public function isOffer(){
        $offers = $this->getAppliedOffers();
        if($offers == NULL){
            return 0;
        }
        
        return count($offers) > 0 ;
    }
    
    /**
     * Get the first Offer (if it exists)
     * @return type 
     */
    public function getOffert(){
        if($this->isOffer()){
            $offerts = $this->getAppliedOffers();
            return $offerts[0];
        }
    }
    
}

?>
