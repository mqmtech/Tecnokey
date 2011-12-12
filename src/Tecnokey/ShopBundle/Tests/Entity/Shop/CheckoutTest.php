<?php

namespace Tecnokey\ShopBundle\Tests\Entity\Shop;

use Symfony\Bundle\DoctrineBundle\Tests\TestCase;
use Symfony\Bundle\SecurityBundle\Tests\Functional\AppKernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Tools\SchemaTool;
use Tecnokey\ShopBundle\Entity\Shop\ShoppingCart;
use Tecnokey\ShopBundle\Entity\Shop\ShoppingCartItem;
use Tecnokey\ShopBundle\Entity\Shop\Order;
use Tecnokey\ShopBundle\Manager\OrderManager;

class CheckoutTest extends TestCase {

    /**
     *
     * @var Symfony\Component\HttpKernel\AppKernel
     */
    protected $kernel;

    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     *
     * @var Symfony\Component\DependencyIntection\Container
     */
    protected $container;
    
    
    public function setUp() {
        // Boot the AppKernel in the test environment and with the debug.
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();

        // Store the container and the entity manager in test case properties
        $this->container = $this->kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getEntityManager();

        // Build the schema for sqlite
        //$this->generateSchema();
        
        $this->shoppingCart = new ShoppingCart();
        
        parent::setUp();
    }
    
    

    protected function generateSchema() {
        // Get the metadatas of the application to create the schema.
        $metadatas = $this->getMetadatas();

        if (!empty($metadatas)) {
            // Create SchemaTool
            $tool = new SchemaTool($this->em);
            $tool->createSchema($metadatas);
        } else {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Overwrite this method to get specific metadatas.
     *
     * @return Array
     */
    protected function getMetadatas() {
        return $this->em->getMetadataFactory()->getAllMetadata();
    }

    public function encodePassword($entity) {
        // encode password //
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($entity);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
        // end encoding password //

        return $entity;
    }
    
    public function testCreateDemoProduct(){
        
        $product = new \Tecnokey\ShopBundle\Entity\Shop\Product();
        $product->setName("_testDemo Product 1");
        $product->setDescription("Description Demo Product");

        $product->setSku("pd1");
        $product->setBasePrice(100.0);
        
       //Store product
       $this->em->persist($product);
       $this->em->flush();
       //End Store product
    }
    
    /**
     * @depends testCreateDemoProduct
     */
    public function testCreateShoppingCart() {
        //Create Shopping cart
        $shoppingCart = new ShoppingCart();
        $shoppingCart->setName('_testShCart1');
        //End creating shopping cart
                        
        //Set User to database
        $this->em->persist($shoppingCart);
        $this->em->flush();
        //End setting user to database
        
    }
    
    /**
     * @depends testCreateShoppingCart
     */
    public function testAddProductToShoppingCart(){
        /*//Retrieve Product
        $product = $this->em->getRepository('TecnokeyShopBundle:Shop\Product')->findOneBy(array('sku' => 'pd1'));
        $this->assertNotNull($product->getName());
        //End Retrieving Product
        
        //Retrieve Shopping Cart
        $shoppingCart = $this->em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->findOneBy(array('name' => '_testShCart1'));
        $this->assertEquals($shoppingCart->getName(), '_testShCart1');
        //End Retrieve Shopping Cart
        
        //Create new ShoppingCartItem
        $shCartItem = new ShoppingCartItem();
        $shCartItem->setProduct($product);
        $shCartItem->setQuantity(1); //By default it must always be 1
        //End Create new ShoppingCartItem
        
        //Set item to Shopping Cart
        $shoppingCart->addItem($shCartItem);
        //End Set item to Shopping Cart
        
        //Store to DB
        $this->em->persist($shoppingCart);
        $this->em->flush();
        //End Storing to DB*/
    }
    
      /**
     * @depends testAddProductToShoppingCart
     */
    public function testCreateOrder(){
        //Retrieve Shopping Cart
        $shoppingCart = $this->em->getRepository('TecnokeyShopBundle:Shop\ShoppingCart')->findOneBy(array('name' => '_testShCart1'));
        $this->assertEquals($shoppingCart->getName(), '_testShCart1');
        //End Retrieve Shopping Cart
        //$this->order = OrderManager::getInstance()->newOrderFromShoppingCart($shoppingCart);
    }
    
    /**
     * CLear Database
     * 
     * TODO: Use a specific database only for testing
     * TODO: Check why it's not possible to remove the ShoppingCartItem elements after deleting a ShoppingCart 
     * (better use a OneToMany relationship instead ManyToMany between SHC and SHCItem ?)
     * 
     * @depends testAddProductToShoppingCart
     */
    public function testClearDatabase(){
      
        //Retrieve all ShoppingCart elements
        $q = $this->em->createQuery("SELECT shc FROM TecnokeyShopBundle:Shop\ShoppingCart shc WHERE shc.name LIKE '_test%'");
        $shcarts = $q->getResult();
        //End retrieving all Shoppingcart elements
        
        //Delete all ShoppingCart elements
        foreach ($shcarts as $shcart) {
           
            //Delete the shopping Cart
            $this->em->remove($shcart);
            //End Deleting the shopping Cart
        }
        $this->em->flush();
        //End deleting all Shoppingcart elements

    }
}
