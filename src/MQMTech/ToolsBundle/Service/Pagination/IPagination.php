<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Pagination;

/**
 * Description of IPagination
 *
 * @author mqmtech
 */
interface IPagination {
    
    /**
     * @return integer returns the index of array of pages
     */
    public function getCurrentPage();
    
    /**
     * @param integer set the index in the array of pages
     */
    public function setCurrentPage($pageIndex);

    /**
     * @return array<IPage>
     */
    public function getPages();
    
    /**
     * @param integer $pageLength
     */
    public function setPageLength($pageLength);
    
    /**
     * @return integer $pageLength
     */
    public function getPageLength();
    
    /**
     * @param integer $totalItems
     */
    public function setTotalItems($totalItems);
    
    /**
     * @return integer $totalItems
     */
    public function getTotalItems();
    
    /**
     *
     * @return IPage
     */
    public function getPrevPage();
    
    /**
     *
     * @return IPage
     */
    public function getNextPage();
    
    /**
     *
     * @return IPage
     */
    public function getFirstPage();
    
    /**
     *
     * @return IPage
     */
    public function getLastPage();
    /**
     * @param array $array
     * @return array
     */
    public function sliceArray($array);
    
    /**
     * Initialize function
     * 
     * Recalc pagination
     * @param int $totalItems
     */
    public function calcPagination($totalItems=NULL);
    

}

?>
