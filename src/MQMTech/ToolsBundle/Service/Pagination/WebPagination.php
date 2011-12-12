<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Pagination;

use Symfony\Component\HttpFoundation\Request;
use MQMTech\ToolsBundle\Service\Utils;
/**
 * Description of WebPagination
 *
 * @author mqmtech
 */
class WebPagination implements IPagination {

    const DEF_PAGE_LENGTH = 10;
    const DEF_TOTAL_ITEMS = 0;
    const DEF_CURRENT_PAGE= 0;
    const DEF_CURRENT_OFFSET= 0;
    const REQUEST_QUERY_PARAM= 'page';
    
    /**
     *
     * @var integer $length
     */
    private $pageLength;
    
    /**
     *
     * @var Array $pages
     */
    private $pages;
    
    /**
     *
     * @var int $currentPage
     */
    private $currentPage;
    
    /**
     *
     * @var integer $totalItems
     */
    private $totalItems;
    
    /**
     *
     * @var WebPageFactory
     */
    private $pageFactory;
    
    /**
     *
     * @var Request
     */
    private $request;
    
    /**
     *
     * @var string
     */
    private $responsePath;
    
    /**
     *
     * @var array
     */
    private $responseParameters;
    
    public function __construct(Request $request, WebPageFactory $pageFactory, $totalItems = self::DEF_TOTAL_ITEMS, $pageLength = self::DEF_PAGE_LENGTH, $currentPageIndex = self::DEF_CURRENT_PAGE, $responsePath=NULL, $responseParameters = NULL) {
        $this->setRequest($request);
        $this->setPageFactory($pageFactory);
        $this->setTotalItems($totalItems);
        $this->setPageLength($pageLength);
        $this->setCurrentPage($currentPageIndex);
        $this->setResponsePath($responsePath);
        $this->setResponseParameters($responseParameters);
    }
    
        /**
     *
     * @param array $totalItems totalItems should be removed from this function as there's a setter method to set the totalItems
     * @return WebPagination 
     */
    public function calcPagination($totalItems = NULL) {
        $this->totalItems = $totalItems == NULL ? $this->totalItems : $totalItems;
        
        $pagesCount = $this->getTotalItems() / $this->getPageLength();
        $pagesCount = floor($pagesCount);
        
        if($this->getTotalItems() > ($pagesCount * $this->getPageLength())){
            $pagesCount+=1;
        }
        
        //Generate Pages Array
        $index = 0;
        for (; $index < $pagesCount; $index++) {
            $offset = $this->getPageLength() * $index;
            $limit = $offset + $this->getPageLength();
            if($limit > $this->getTotalItems()){
                $limit = $this->getTotalItems();
            }
            
            $page = $this->pageFactory->buildPage();
            $page->setId($index);
            $page->setOffset($offset);
            $page->setLimit($limit);
            $page->setResponsePath($this->getResponsePath());
            $responseParameters = $this->getResponseParameters();
            if($responseParameters == NULL){
                $responseParameters = Utils::getInstance()->getAllParametersFromRequestAndQuery($this->getRequest());
            }
            $page->setResponseParameters($responseParameters);
            
            if($this->pages == NULL){
                $this->pages = array();
            }
            $this->pages[$index] = $page;
            
        }// End Generate Pages Array
        
        if($index > 0){
            //Grab Current Page from Request
            $query = Utils::getInstance()->getParametersByRequestMethod($this->getRequest());       
            $currentPage = $query->get(self::REQUEST_QUERY_PARAM) == NULL ? self::DEF_CURRENT_PAGE : $query->get(self::REQUEST_QUERY_PARAM);
            //End grabbing curent page from Request
            $lastPage = count($this->getPages()) -1;
            if($currentPage > $lastPage){
                $currentPage = $lastPage;
            }
            else if($currentPage <= 0){
                $currentPage = 0;
            }
            if(isset ($this->pages[$currentPage])){
                $this->pages[$currentPage]->setIsCurrent(true);
                $this->setCurrentPage($currentPage);
            }
        }
        
        
        return $this;        
    }
    
    /**
     *
     * Slice an array according to the currentPage
     * 
     * @param Array $array 
     */
    public function sliceArray($array){
        if($array == NULL){
            return NULL;
        }
        
        $currentPage = NULL;
        if(isset($this->pages[$this->getCurrentPage()])){
             $currentPage = $this->pages[$this->getCurrentPage()];
        }
        else{
            return $array;
        }
                
        if(is_array($array)){
             $array = array_slice($array, $currentPage->getOffset(), $this->getPageLength());
            }
            else if(is_a($array, 'Doctrine\ORM\PersistentCollection')){
                $array->slice($currentPage->getOffset(), $this->getPageLength());
            }
                else{
                    //Do nothing
                }
        return $array;
    }
    
        /**
     *
     * @return Page
     */
    public function getPrevPage(){
        $currentPage = $this->getCurrentPage();
        
        if($currentPage <= 0 ){
            return $this->pages[$currentPage];
        }
        else{
            return $this->pages[$currentPage - 1];
        }
    }
    
    /**
     *
     * @return Page
     */
    public function getNextPage(){
        $currentPage = $this->getCurrentPage();
        
        if( $currentPage >= count($this->getPages()) -1 ){
            return $this->pages[$currentPage];
        }
        else{
            return $this->pages[$currentPage + 1];
        }
        
    }
    
    /**
     *
     * @return Page
     */
    public function getFirstPage(){
        return $this->pages[0];
    }
    
    /**
     *
     * @return Page
     */
    public function getLastPage(){
       $length = count($this->pages);
       return $this->pages[$length -1];
    }
    
    public function getResponsePath() {
        return $this->responsePath;
    }

    public function setResponsePath($responsePath) {
        $this->responsePath = $responsePath;
    }

    public function getResponseParameters() {
        return $this->responseParameters;
    }

    public function setResponseParameters($responseParameters) {
        $this->responseParameters = $responseParameters;
    }

        
    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function getPageLength() {
        return $this->pageLength;        
    }
    public function getPages() {
        return $this->pages;
    }
    
    public function setCurrentPage($pageIndex) {
        $this->currentPage = $pageIndex;
    }
    public function setPageFactory(IPageFactory $pageFactory) {
        $this->pageFactory = $pageFactory;
    }
    public function setPageLength($pageLength) {
        $this->pageLength = $pageLength;
    }

    public function getTotalItems() {
        return $this->totalItems;
    }

    public function setTotalItems($totalItems) {
        $this->totalItems = $totalItems;
    }

    public function getRequest() {
        return $this->request;
    }

    public function setRequest($request) {
        $this->request = $request;
    }
}

?>
