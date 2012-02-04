<?php

namespace Tecnokey\ShopBundle\Tests\Service;

use Symfony\Bundle\DoctrineBundle\Tests\TestCase;
use Symfony\Bundle\SecurityBundle\Tests\Functional\AppKernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Tools\SchemaTool;
use Tecnokey\ShopBundle\Entity\Shop\Product;

class CheckoutManagerTest extends TestCase {


    /**
     * Overwrite this method to get specific metadatas.
     *
     * @return Array
     */
    protected function getMetadatas() {
        return $this->em->getMetadataFactory()->getAllMetadata();
    }
    
    public function testSimple() {
        //Create ShoppingCart
        
        //Product
        $productA= new Product();
        $productA->setName('productA');
        $productA->setBasePrice(100.0);
        
        $productB= new Product();
        $productB->setName('productB');
        $productB->setBasePrice(200.0);
        
        //TimeOffer
        $timeOfferA = new \Tecnokey\ShopBundle\Entity\Shop\TimeOffer();
        $timeOfferA->setStart(new \DateTime('yesterday'));
        $timeOfferA->setDeadline(new \DateTime('tomorrow'));
        $timeOfferA->setDiscount(50);
        
        //Set TimeOffer to ProductB
        $productB->setOffer($timeOfferA);
        
        //Create ShoppingCart
        $shoppingCart = new \Tecnokey\ShopBundle\Entity\Shop\ShoppingCart();
        
        //Create ShoppingCartItems
        $itemA = new \Tecnokey\ShopBundle\Entity\Shop\ShoppingCartItem();
        $itemA->setProduct($productA);
        $itemA->setQuantity(2);
        
        $itemB = new \Tecnokey\ShopBundle\Entity\Shop\ShoppingCartItem();
        $itemB->setProduct($productB);
        $itemB->setQuantity(2);
        
        //Add Items to ShoppingCart
        $shoppingCart->addItem($itemA);
        $shoppingCart->addItem($itemB);
        
        //Create MarketManager
        $marketManager = new \Tecnokey\ShopBundle\Service\MarketManager();
        
        //Create PriceManager
        $priceManager = new \Tecnokey\ShopBundle\Service\PriceManager();
        
        //Create CheckoutManager
        $checkoutManager = new \Tecnokey\ShopBundle\Service\CheckoutManager($priceManager, $marketManager);
        
        //Checkout
        $shoppingCart = $checkoutManager->checkout($shoppingCart);
        
        //generate Order
        $order = $checkoutManager->shoppingCartToOrder($shoppingCart);
        
        $this->assertEquals($order->getTotalBasePrice(), $shoppingCart->getTotalBasePrice());
        $this->assertEquals($order->getItems()->next()->getTotalBasePrice(), $shoppingCart->getItems()->next()->getTotalBasePrice());
        
        //print_r($order);

    }

}
