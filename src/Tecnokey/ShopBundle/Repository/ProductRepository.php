<?php
namespace Tecnokey\ShopBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryDAO
 *
 * @author MQMTECH
 */
class ProductRepository extends EntityRepository{
    
    const RECENT_MAX_RESULTS = 10;
    const RECENT_ORDER_BY = 'DESC';
    const RELATED_MAX_RESULTS = 5;

    /**
     *
     * @param type $categoryId
     * @param Array $orderBy [field,mode]
     * @return type 
     */
    public function findByCategoryId($categoryId, $orderBy){
        //Grab categories from db
        $em = $this->getEntityManager();
        
        $sql = "select p from TecnokeyShopBundle:Shop\\Product p JOIN p.category c WHERE c.id = '".$categoryId."' ";
        $sql .= $this->orderBy("p", $orderBy);
 
        $q = $em->createQuery($sql);
        $products = $q->getResult();
        
        return $products;
    }
    
    /**
     * list of products
     * 
     * return array
     */
    public function findByBrandId($id, $orderBy){
        //Grab categories from db
        $em = $this->getEntityManager();
        
        $sql = "select p from TecnokeyShopBundle:Shop\\Product p JOIN p.brand b WHERE b.id = '".$id."' ";
        $sql .= $this->orderBy("p", $orderBy);

        $q = $em->createQuery($sql);
        $products = $q->getResult();
        
        return $products;
    }
    
    public function findRecent($max = self::RECENT_MAX_RESULTS){
       
        $em = $this->getEntityManager();

        //Grab categories from db
        $em = $this->getEntityManager();
        $q = $em->createQuery("select p from TecnokeyShopBundle:Shop\\Product p ORDER BY p.createdAt ".self::RECENT_ORDER_BY);
        $q->setMaxResults($max);
        $products = $q->getResult();

        return $products;
        //End grabbing cats from db
    }

    
    public function findRelatedProducts($productId, $maxResults = self::RELATED_MAX_RESULTS){
        $em = $this->getEntityManager();

        $product = $this->find($productId);
        
        $tag = $product->getTag();
        $secondTag = $product->getSecondTag();
        $thirdTag = $product->getThirdTag();
        $fourthTag = $product->getFourthTag();
        
        $sql = "SELECT p FROM TecnokeyShopBundle:Shop\\Product p WHERE p.id <> '".$productId."'";
        
        $sqlTag = $this->tagEqualHelper($tag);
        $sqlSecondTag = $this->tagEqualHelper($secondTag);
        $sqlThirdTag = $this->tagEqualHelper($thirdTag);
        $sqlFourthTag = $this->tagEqualHelper($fourthTag);
        
        $sql = $sql . " AND (" .$sqlTag ." OR " .$sqlSecondTag ." OR " .$sqlThirdTag ." OR " .$sqlFourthTag . ")";
        
        //echo $sql;
        
        $q = $em->createQuery($sql);
        
        $q->setMaxResults($maxResults);
        $products = $q->getResult();
        
        return $products;
        //End grabbing cats from db
    }
    
    public function tagEqualHelper($tag){
        $sql = "";
        
        for ($index = 0; $index < 4; $index++) {
            $sql   =    "p.tag LIKE '%".$tag."%' OR " . "p.secondTag LIKE '%".$tag."%' OR " . "p.thirdTag LIKE '%".$tag."%' OR " . "p.fourthTag LIKE '%".$tag."%'";
        }
        return $sql;
    }
    
  /**
     * The orderBy Parameter must be the property and the type 'ASC' 'DESC' , ex: name ASC
     * 
     * @param string $word
     * @param string $mode
     * @param Array $orderBy
     * @return array 
     */
    public function searchByWordFull($word, $mode="OR", $orderBy=NULL){
        //Grab propduct from db
        $em = $this->getEntityManager();
        
        $query = "";
        //Name and description search
        $query .= " WHERE p." . "name" . " LIKE '%" . $word . "%'";
        $query .= " OR p." . "description" . " LIKE '%" . $word . "%'";
        
        //Tag search
        $query .= " OR p." . "tag" . " LIKE '%" . $word . "%'";
        $query .= " OR p." . "secondTag" . " LIKE '%" . $word . "%'";
        $query .= " OR p." . "thirdTag" . " LIKE '%" . $word . "%'";
        $query .= " OR p." . "fourthTag" . " LIKE '%" . $word . "%'";
        
        //Sku Search
        $query .= " OR p." . "sku" . " LIKE '%" . $word . "%'";
        
        //Brand search
        $query .= " OR b." . "name" . " LIKE '%" . $word . "%'";
        $query .= " OR b." . "description" . " LIKE '%" . $word . "%'";
        
        $query .= $this->orderBy("p", $orderBy);
        
        $q = $em->createQuery("select p from TecnokeyShopBundle:Shop\\Product p JOIN p.brand b" . $query);
        $products= $q->getResult();
        //End grabbing products from db
        
        return $products;
    }
    
    /**
     *
     * @param string $entity
     * @param array $orderBy 
     */
    public function orderBy($entity, $orderBy){
        $sql = "";
        if($orderBy!=NULL){
            $sql .=" ORDER BY";
            foreach ($orderBy as $key => $value) {
                $sql .=" ".$entity.".".$key." ".$value;
            }            
        }
        return $sql;
    }
}

?>
