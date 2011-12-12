<?php

namespace Tecnokey\ShopBundle\Tests\Entity\Shop;

use Symfony\Bundle\DoctrineBundle\Tests\TestCase;
use Symfony\Bundle\SecurityBundle\Tests\Functional\AppKernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Tools\SchemaTool;

use Tecnokey\ShopBundle\Entity\Shop\Image;
use Tecnokey\ShopBundle\Entity\Shop\Product;

class ProductTest extends TestCase {

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
    
    public function testCreateProduct() {
        $image = new Image();
        $image->setName("Demo Image");
        
        $product = new Product();
        $product->setName("Product Demo");
        $product->setImage($image);
        
                        
        //Set User to database
        $this->em->persist($product);
        $this->em->flush();
        //End setting user to database
    }
 
    
    /**
     * CLear Database
     * @depends testCreateProduct
     */
    public function testClearDatabase(){
      
        //Retrieve all Products 
        $q = $this->em->createQuery("SELECT p FROM TecnokeyShopBundle:Shop\Product p");
        $products = $q->getResult();
        //End retrieving all Products
        
        //Delete all Products
        foreach ($products as $product) {
            //Delete the Product
            $this->em->remove($product);
            //End Deleting the Product
        }
        $this->em->flush();
        //End deleting all Products
    }
}
