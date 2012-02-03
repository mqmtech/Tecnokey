<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Tecnokey\ShopBundle\Form\Shop\CustomFileType;

use Tecnokey\ShopBundle\Form\DataTransformer\PriceToPrettyPriceTransformer;
use MQMTech\ToolsBundle\Service\Utils;
use Tecnokey\ShopBundle\Service\MarketManager;

class PrettyPriceType extends AbstractType{
    private $utils;
    private $marketManager;
    
    public function __construct(Utils $utils, MarketManager $marketManager)
    {
        $this->utils = $utils;
        $this->marketManager = $marketManager;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $transformer = new PriceToPrettyPriceTransformer($this->utils, $this->marketManager);
        $builder->appendClientTransformer($transformer);
    }
    
    public function getParent(array $options)
    {
        return 'text';
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_shop_prettyprice';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'string',
            'invalid_message'=>'The value is not a valid price'
        );
    }
}

?>
