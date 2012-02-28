<?php

namespace Tecnokey\ShopBundle\Entity\Statistic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tecnokey\ShopBundle\Entity\Statistic\Statistic
 *
 * @ORM\Table(name="shop_statistic_product")
 * @ORM\Entity(repositoryClass="namespace Tecnokey\ShopBundle\Repository\ProductStatisticRepository")
 */
class ProductStatistic extends Statistic
{
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Tecnokey\ShopBundle\Entity\Shop\Product", inversedBy="statistics", cascade={"persist"})
     * @ORM\JoinColumn(name="productId", referencedColumnName="id", nullable=true)
     * 
     * @var Product $product
     */
    private $product;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getProduct() {
        return $this->product;
    }

    public function setProduct($product) {
        $this->product = $product;
    }
    
}