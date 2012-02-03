<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Tecnokey\ShopBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use MQMTech\ToolsBundle\Service\Utils;

/**
 * Description of PriceToPrettyPriceTransformer
 *
 * @author mqmtech
 */
class PriceToPrettyPriceTransformer implements DataTransformerInterface{
    private $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    // transforms the Issue object to a string
    public function transform($val)
    {
        if (null === $val) {
            return '';
        }

        return $this->utils->floatToPrettyFloat($val);
    }

    // transforms the issue number into an Issue object
    public function reverseTransform($val)
    {
        if (!$val) {
            return null;
        }

        $val = substr($val, 0, count($val)-2);
        $float = $this->utils->prettyFloatToFloat($val);

        if (null === $float) {
            throw new TransformationFailedException(sprintf('An issue with number %s does not exist!', $val));
        }

        return $float;
        //return "yeah";
    }
}

?>
