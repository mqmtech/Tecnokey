<?php

namespace Tecnokey\ShopBundle\Controller\Frontend;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Exception;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/imagenes")
 */
class ImageController extends Controller {

    /**
     * Frontend demo
     *
     * @Route("/{imageId}/ver.{_format}", defaults={"_format"="bin"}, name="TKShopFrontendImageShowData")
     */
    public function showDataAction($imageId){
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('TecnokeyShopBundle:Shop\Image')->find($imageId);
        
        if($entity == NULL){
            return new Response("No se encuentra la imagen");
        }
        //if no return...

        /**
        $content = "";
        $path = $entity->getWebPath();
        $content.= "<br/>WebPath: ". $path;
        $content.= "<br/>AbsolutePath: ". $entity->getAbsolutePath();
         * 
         */
        
        $filepath = $entity->getAbsolutePath();
        $file = file_get_contents($filepath);

        $extension = $this->getExtension($entity->getPath());
        
        $imageTypes = Array (
                        "bmp" => "image/bmp", 
                        "jpeg" => "image/jpeg", 
                        "jpg" => "image/jpeg", 
                        "pjpeg" => "image/pjpeg", 
                        "gif" => "image/gif", 
                        "png" => "image/x-png");
        $imageType = $imageTypes[$extension];
        
        $response = new Response($file, 200, array(
            'Content-Type' => $imageType));
        
        return $response;
    }
    
    public function getExtension($path){
        $extension = substr(strrchr($path, "."), 1);
        return $extension;
    }

}
