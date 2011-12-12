<?php

namespace Tecnokey\ShopBundle\Tests\Entity\Shop;

use Symfony\Bundle\DoctrineBundle\Tests\TestCase;
use Symfony\Bundle\SecurityBundle\Tests\Functional\AppKernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Tools\SchemaTool;

use Tecnokey\ShopBundle\Entity\Shop\Image;
use Tecnokey\ShopBundle\Entity\Shop\Product;

class PaginationTest extends TestCase {

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
    
    public function testCreatePagination() {
       // $pagination = $this->container->get('view.pagination2');
    }
 

}
