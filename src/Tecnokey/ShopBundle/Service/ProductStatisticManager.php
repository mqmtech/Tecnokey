<?php

/**
 * ProductStatisticManager makes crud of a shopping cart 
 *
 * @author mqmtech
 */
namespace Tecnokey\ShopBundle\Service;

use Tecnokey\ShopBundle\Entity\Shop\Product;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Tecnokey\ShopBundle\Entity\Statistic\ProductStatistic;

class ProductStatisticManager {
    
    const entityName = 'TecnokeyShopBundle:Statistic\ProductStatistic';

    /**
     *
     * @var RegistryInterface
     */
    private $doctrine;
    
    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }
    
    /**
     * Clear the items in the Shopping Cart
     * Important note: it's needed to perform an em->persist() / em->flush() to make this changes permanent
     * 
     * @param ProductStatistic $productStatistic
     * @param int productId
     * 
     * @return \Tecnokey\ShopBundle\Entity\Statistic\ProductStatistic
     */
    public function registerStatToProduct(ProductStatistic $productStatistic, Product $product){
        if($productStatistic == NULL || $product == NULL){
            return NULL;
        }
        $statistics = $product->getStatistics();
        
        $productStatistic->setProduct($product); // This one will modify the database (when persist and flush is done)
        $statistics->add($productStatistic);     // This one update the live product object
        
        return $productStatistic;        
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
            throw new \Exception("Custom Exception: No DatabaseManager has been setted in ProductStatisticManager");
        }
    }
}

?>
