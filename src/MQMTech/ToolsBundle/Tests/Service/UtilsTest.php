<?php

namespace Tecnokey\ShopBundle\Tests\Entity\Shop;

use Symfony\Bundle\DoctrineBundle\Tests\TestCase;
use Symfony\Bundle\SecurityBundle\Tests\Functional\AppKernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Tools\SchemaTool;

use Tecnokey\ShopBundle\Entity\Shop\Image;
use Tecnokey\ShopBundle\Entity\Shop\Product;

class UtilsTest extends TestCase {

    public function testPrettyFloat() {
       $fooFloat = 5000.56;
       $fooPrettyFloat = \MQMTech\ToolsBundle\Service\Utils::getInstance()->floatToPrettyFloat($fooFloat);
       $fooFloatFromPretty = \MQMTech\ToolsBundle\Service\Utils::getInstance()->prettyFloatToFloat($fooPrettyFloat);
       $this->assertEquals($fooFloatFromPretty,5000.56);
       
    }
}
