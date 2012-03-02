<?php

namespace MQMTech\ToolsBundle\Twig\Extension;

class ToolsExtension extends \Twig_Extension {
    
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'ToolsTwigExtension';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'totime' => new \Twig_Function_Method($this, 'toTime'),
        );
    }
    
    public function getFilters() {
        return array(
            'totime' => new \Twig_Filter_Method($this, 'toTime'),
            'yeah' => new \Twig_Filter_Method($this, 'yeah'),
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
    
    public function yeah($string){
        return "yeah! " . $string;
    }
    
}