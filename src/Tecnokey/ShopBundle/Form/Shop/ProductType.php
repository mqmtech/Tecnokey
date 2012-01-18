<?php

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\DoctrineBundle\Registry;

use Tecnokey\ShopBundle\Form\DataTransformer\PriceToPrettyPriceTransformer;

class ProductType extends AbstractType
{

    /**
     *
     * @var Registry
     */
    public $doctrine = NULL;
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $priceToPrettyPriceTransformer = new PriceToPrettyPriceTransformer(new \MQMTech\ToolsBundle\Service\Utils());
        
        $builder
            ->add('id', 'hidden')
            ->add('name', null, array(
                'label' => 'add_titulo',
                'max_length' => '70'
            ))
            ->add('description')
            ->add('sku', null, array(
                'label' => 'add_ref'
            ))
            ->add('basePrice', null, array(
            //->add('basePrice', 'prettyPrice', array(
                'label' => 'add_precio'
            ))
            ->add('offer', new TimeOfferType())
            ->add('category', 'entity', array(
             'class' => 'Tecnokey\\ShopBundle\\Entity\\Shop\\Category',
             'empty_value' => 'Categorias...',
             'required' => true,
             'label' => 'Categoria',
             'choices' => $this->buildOrdenedCategoriesChoiceArray()
              ))
            ->add('image', new ImageType(), array(
                'required' => false,
                'label' => 'Imagen principal',
            ))            
            ->add('secondImage', new ImageType())            
            ->add('thirdImage', new ImageType())            
            ->add('fourthImage', new ImageType())            
            ->add('tag', null, array(
                'label' => ' add_tags'
            ))
            ->add('secondTag', null, array(
                'label' => ' add_tags'
            ))
            ->add('thirdTag', null, array(
                'label' => ' add_tags'
            ))
            ->add('fourthTag', null, array(
                'label' => ' add_tags'
            ))
            
            ->add('brand', 'entity', array(
            'empty_value' => 'Marcas...',
            'class' => 'Tecnokey\\ShopBundle\\Entity\\Shop\\Brand',
            'required' => true,
            'label' => 'Marca'
              ));
    }    
    /**
     *
     * @param ArrayCollection $categories
     * @param array $categoriesChoice
     * @return array 
     */
    public function buildOrdenedCategoriesChoiceArray(PersistentCollection $categories=NULL, array &$categoriesChoice = NULL){
        
        if($categoriesChoice == NULL){
            $categoriesChoice = array();
        }
        
        if($categories == NULL){
            $categories = (array) $this->doctrine->getEntityManager()->getRepository('TecnokeyShopBundle:Shop\Category')->findAllFamilies();
        }
        
        foreach ($categories as $category) {
            $categoriesChoice[$category->getId()] = $category;
            $subCategories = $category->getCategories();
            if($subCategories != NULL){
               $this->buildOrdenedCategoriesChoiceArray($subCategories, $categoriesChoice);
            }
        }
        
        return $categoriesChoice;
    }
    
    public function getName()
    {
        return 'tecnokey_shopbundle_shop_producttype';
    }
        
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Tecnokey\ShopBundle\Entity\Shop\Product',
        );
    }
    
    /**
     *
     * @param Registry $doctrine 
     */
    public function __construct(Registry $doctrine) {
            $this->doctrine = $doctrine;
    }
    
    /**
     *
     * @param Registry $doctrine 
     */
    public function setDoctrine(Registry $doctrine){
        $this->doctrine = $doctrine;               
    }
}
