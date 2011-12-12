<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Pagination;
/**
 *
 * @author mqmtech
 */
interface IPageFactory {
   
    /**
     * @return IPage
     */
    public function buildPage();    
}

?>
