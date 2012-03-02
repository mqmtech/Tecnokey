<?php

# Test/MyBundle/Twig/Extension/MyBundleExtension.php

namespace Tecnokey\ShopBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Tecnokey\ShopBundle\Service\OrderManager;

class OrderManagerExtension extends \Twig_Extension {
    
    /**
     *
     * @var Container
     */
    private $orderManager;
    
    
    public function __construct(OrderManager $orderManager) {
        $this->orderManager = $orderManager;
    }
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'Shop.OrderManager';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'totime' => new \Twig_Function_Method($this, 'toTime'),
            'stateToDescription' => new \Twig_Function_Method($this, 'stateToDescription'),
        );
    }
    
    public function getFilters() {
        return array(
            'totime' => new \Twig_Filter_Method($this, 'toTime'),
            'stateToDescription' => new \Twig_Filter_Method($this, 'stateToDescription'),
        );
    }

    /**
     * Converts a string to time
     * 
     * @param string $string
     * @return int 
     */
    public function toTime($string) {
        return strtotime($string);
    }
    
   public function stateToDescription($state) {
       $orderManager = $this->getOrderManager();
       return $orderManager->getStateDescription($state);
       
    }
    
    /**
     *
     * @return OrderManager
     */
    public function getOrderManager() {
        return $this->orderManager;
    }

    public function setOrderManager($orderManager) {
        $this->orderManager = $orderManager;
    }



}