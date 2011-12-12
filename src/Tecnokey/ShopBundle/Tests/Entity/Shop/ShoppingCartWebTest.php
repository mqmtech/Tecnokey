<?php

namespace Tecnokey\ShopBundle\Tests\Entity\Shop;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Tecnokey\ShopBundle\Entity\Shop\ShoppingCart;

class ShoppingCartWebTest extends WebTestCase
{
    public function testIndex()
    {
        $scart = new ShoppingCart('shopping cart', 'this is a shopping cart');
        
    }
}
