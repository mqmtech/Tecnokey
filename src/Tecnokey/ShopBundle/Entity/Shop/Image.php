<?php

namespace Tecnokey\ShopBundle\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\BooleanType;

/**
 * Tecnokey\ShopBundle\Entity\Shop\Image
 *
 * @ORM\Table(name="shop_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image {
    
    const DEF_WIDTH = 68;
    const DEF_HEIGHT = 68;
    const DEF_H_PADDING = 0;
    const DEF_V_PADDING = 0;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var datetime $modifiedAt
     *
     * @ORM\Column(name="modifiedAt", type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @var float $size
     *
     * @ORM\Column(name="size", type="float", nullable=true)
     */
    private $size;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var boolean $fileUpdated
     *
     * @ORM\Column(name="fileUpdated", type="boolean", nullable=true)
     */
    private $fileUpdated;

    /**
     * virtual file field needed in the form
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    //** Factory
    
    /**
     * Constructs a new instance of Image.
     */
    public function __construct() {
        $this->setFileUpdated(false);
        $this->createdAt = new \DateTime();
    }
    
    function __clone(){
        
        //generate new file
        $path = uniqid().'.jpg';//.$this->file->guessExtension());
        copy($this->getAbsolutePath(), $this->getUploadRootDir() . '/' . $path);
        $this->setPath($path);
        // end generating new file
        
        //Set the default name
        if ($this->getName() == null) {
            $this->setName($this->getPath());
        }
        //end setting the default name
    }
    
    //** End Factory
    
    //** Functions
        public function getAbsolutePath() {
        
        if($this->path == NULL){
            $path = 'image_nd.jpg';
             return $this->getPrivateRootDir() . '/' . $path;
        }
        else{
        ////or///
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->getPath();
        ////end or///
        }
    }

    public function getWebPath() {
        //return null === $this->path ? null : $this->getUploadDir() . '/' . $this->getPath();
        if($this->path == NULL){
            //default image-path
            $path = 'image_nd.jpg';
            return $this->getPrivateDir() . '/' . $path;
        }
        else{
            return $this->getUploadDir() . '/' . $this->getPath();
        }
    }
    
    protected function getPrivateRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../../web/' . $this->getPrivateDir();
    }

    public function getUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'bundles/tecnokey/uploads/images';
    }
    
    protected function getPrivateDir() {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'bundles/tecnokey/images';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        $this->modifiedAt = new \DateTime();

        if (null !== $this->file) {
            $this->setPath(uniqid().'.jpg');//.$this->file->guessExtension());
        }

        //Set the default name
        if ($this->getName() == null) {
            $this->setName($this->getPath());
        }
        //end setting the default name
    }
    
     /**
     * Set the absolute path in the non-persistent variable file to be used in the postRemove if the remove is succesful
     * @ORM\PreRemove()
     */
    public function preRemove() {
        $path = $this->getPath();
        
        if($path != NULL){
            $this->file = $this->getAbsolutePath();
        }
    }
    
     /**
     * Deletes the file from the system completely
     * Reads the file variable which must have temporally the absolute path of the path setted in the PreRemove listener
     * @ORM\PostRemove()
     */
    public function postRemove() {
        $absolutePath = $this->getFile();
        
        if($absolutePath != NULL){
            try{
                unlink($absolutePath);
            }
            catch(\Exception $e){
                
            }
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->file->move($this->getUploadRootDir(), $this->getPath());

        unset($this->file);
    }

    public function __toString() {
        return "" . $this->name;
    }
    
    /**
     *
     * @return boolean
     */
    public function isAvailable(){
        return $this->path == NULL;
    }
    
    /**
     *
     * @return float
     */
    public function getWidth(){
        $path = $this->getAbsolutePath();
        if($path == NULL){
            return NULL;
        }
        list($width, $height, $type, $attr)= getimagesize($path);
        
        return $width;
    }
    
    /**
     *
     * @return float
     */
    public function getHeight(){
        $path = $this->getAbsolutePath();
        if($path == NULL){
            return NULL;
        }
        list($width, $height, $type, $attr)= getimagesize($path);
        
        return $height;
    }
    
    /**
     *
     * @param float $maxWidth
     * @param float $maxHeight
     * @return Array 
     */
    public function getImageSize($maxWidth=NULL, $maxHeight=NULL){
        $path = $this->getAbsolutePath();
        try{
        list($width, $height, $type, $attr)= getimagesize($path);
        }
        catch(\Exception $e){
            return array(
            'width' => self::DEF_WIDTH,
            'height' => self::DEF_HEIGHT,
            'hPadding' => self::DEF_H_PADDING,
            'vPadding' => self::DEF_V_PADDING,
            );
        }
        
        $width += 0.0;
        $height += 0.0;
        $newWidth = $width + 0.0;
        $newHeight = $height + 0.0;
        
        $vPadding = 0.0;
        $hPadding = 0.0;
        
        if($maxWidth!=NULL && $maxHeight != NULL){
            if($width > $height){
                $newWidth = $maxWidth;
                $proportion = ($newWidth / $width) + 0.0;
                $newHeight = $height * $proportion;
            }
            else{
                $newHeight = $maxHeight;
                $proportion = ($newHeight / $height) + 0.0;
                $newWidth = $width * $proportion;
            }
            
            $hPadding = ($maxWidth - $newWidth) / 2.0;
            $vPadding = ($maxHeight - $newHeight) / 2.0;
        }
        
        return array(
            'width' => $newWidth,
            'height' => $newHeight,
            'hPadding' => $hPadding,
            'vPadding' => $vPadding,
        );
    }
    //** End Functions

    /**
     *
     * @return File 
     */
    public function getFile() {
        return $this->file;
    }

    /**
     *
     * @param File $file 
     */
    public function setFile($file) {
        $this->file = $file;
    }

    /**
     * @return boolean
     */
    public function isFileUpdated() {
        return $this->fileUpdated;
    }

    /**
     *
     * @param boolean $fileUpdated 
     */
    public function setFileUpdated($fileUpdated) {
        $this->fileUpdated = $fileUpdated;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param datetime $modifiedAt
     */
    public function setModifiedAt($modifiedAt) {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * Get modifiedAt
     *
     * @return datetime 
     */
    public function getModifiedAt() {
        return $this->modifiedAt;
    }

    /**
     * Set size
     *
     * @param float $size
     */
    public function setSize($size) {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return float 
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Set path
     *
     * @param string $path
     */
    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

}