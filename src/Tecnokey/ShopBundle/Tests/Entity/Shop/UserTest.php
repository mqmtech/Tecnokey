<?php

namespace Tecnokey\ShopBundle\Tests\Entity\Shop;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tecnokey\ShopBundle\Entity\Shop\User;
use Tecnokey\ShopBundle\Entity\Shop\ShoppingCart;
use Tecnokey\ShopBundle\Entity\Shop\Order;
use Symfony\Bundle\SecurityBundle\Tests\Functional\AppKernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Tools\SchemaTool;

class UserTest extends \Symfony\Bundle\DoctrineBundle\Tests\TestCase {

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
    
    public function testCreateUser() {
        $user = new User('tclient3', '123456', 'ROLE_SUPER_ADMIN');

        //Encode password
        $user = $this->encodePassword($user);
        $this->assertNotEquals('123456',$user->getPassword());
        //End encoding password
        
        //Get shopping cart
        $shcart = $user->getShoppingCart();
        $this->assertEquals(NULL, $shcart);
        //End getting shopping cart
        
        //Create Shopping cart
        $shcart = new ShoppingCart();
        $shcart->setName('sc_'.$user->getUsername());
        $user->setShoppingCart($shcart);
        $this->assertNotEquals(NULL, $shcart);
        //End creating shopping cart
        
        //Create Order
        $order = new Order();
        $order->setName('sc_'.$user->getUsername());
        $user->addOrder($order);
        //End creating order
        
        //Set User to database
        $this->em->persist($user);
        $this->em->flush();
//        //End setting user to database
        
    }
    
      
   /**
     * testRetrieveUser
     * @depends testCreateUser
     */
    public function testRetrieveUser(){
        //Get user from DB
        $entity = $this->em->getRepository('TecnokeyShopBundle:Shop\User')->findOneBy(array('username' => 'tclient3'));
        $this->assertEquals($entity->getUsername(), 'tclient3');
        //End Getting user from DB
        
        //Verifing shopping cart
        $shCart = $entity->getShoppingCart();
        
        $this->AssertEquals($shCart->getName(), 'sc_'.$entity->getUsername());
        //End Verifing shopping cart
    }
    
    /**
     * testRetrieveUser
     * @depends testRetrieveUser
     */
    public function testAddShoppingItems(){
        //Get user from DB
        $entity = $this->em->getRepository('TecnokeyShopBundle:Shop\User')->findOneBy(array('username' => 'tclient3'));
        $this->assertEquals($entity->getUsername(), 'tclient3');
        //End Getting user from DB
        
        //Verifing shopping cart
        $shCart = $entity->getShoppingCart();
        
        $this->AssertEquals($shCart->getName(), 'sc_'.$entity->getUsername());
        //End Verifing shopping cart
        
        $item = new \Tecnokey\ShopBundle\Entity\Shop\ShoppingCartItem();
        $item->setProduct(new \Tecnokey\ShopBundle\Entity\Shop\Product());
        $shCart->addItem($item);
    }
    
    /**
     * testRetrieveUser
     * @depends testAddShoppingItems
     */
    public function testAddOrderItems(){
        //Get user from DB
        $entity = $this->em->getRepository('TecnokeyShopBundle:Shop\User')->findOneBy(array('username' => 'tclient3'));
        $this->assertEquals($entity->getUsername(), 'tclient3');
        //End Getting user from DB
        
        //Verifing shopping cart
        $orders = $entity->getOrders();
        
        $this->assertGreaterThan(0, count($orders));
        //End Verifing shopping cart
        
        $order = $orders[0];
        $item = new \Tecnokey\ShopBundle\Entity\Shop\OrderItem();
        $item->setProduct(new \Tecnokey\ShopBundle\Entity\Shop\Product());
        $order->addItem($item);
    }
    
    /**
     * CLear Database
     * @depends testRetrieveUser
     */
    public function testClearDatabase(){
      
        //Retrieve all Products 
        $q = $this->em->createQuery("SELECT u FROM TecnokeyShopBundle:Shop\User u");
        $entities = $q->getResult();
        //End retrieving all Products
        
        //Delete all Products
        foreach ($entities as $entity) {
            //Delete the Product
            $this->em->remove($entity);
            //End Deleting the Product
        }
        $this->em->flush();
        //End deleting all Products
    }
}
