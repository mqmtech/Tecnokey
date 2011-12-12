<?php

namespace Tecnokey\ShopBundle\Controller\Frontend;

use Tecnokey\ShopBundle\Entity\Shop\Brand;
use Tecnokey\ShopBundle\Entity\Shop\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tecnokey\ShopBundle\Entity\Shop\Category;
use Tecnokey\ShopBundle\Form\Shop\CategoryType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Exception;

/**
 * Frontend\Default controller.
 *
 * @Route("/tienda/contacto")
 */
class ContactController extends Controller {

    /**
     *
     * @Route("/", name="TKShopFrontendContactIndex")
     * @Template("TecnokeyShopBundle:Default:contacto.html.twig")
     */
    public function indexAction() {
        return array();
    }
    
    /**
     *
     * @Route("/enviar_consulta", name="TKShopFrontendContactSendEmail")
     * @Method("post")
     * @template("TecnokeyShopBundle:Default:contacto.html.twig")
     */
    public function sendEmailAction() {
        
        $request = Request::createFromGlobals();
        
        //Get request parameters
        // the URI being requested (e.g. /about) minus any query parameters
        //$request->getPathInfo();

        // retrieve GET and POST variables respectively
        //$request->query->get('foo');
        //$request->request->get('bar');
        
        $company = $request->request->get("company");
        $email = $request->request->get("email");
        $message = $request->request->get("message");
                
       $form_feedback = $this->getFormvalidationFeedback($company, $email, $message);
        $this->get('session')->setFlash('form_feedback', $form_feedback);
        if($form_feedback["success"] != NULL){
            //Send email
            //End getting request parameters
            $message = \Swift_Message::newInstance()
            ->setSubject('Consulta de '. $company.' para Tecknokey')
            ->setFrom("consultas@tecnokey.es")
            //->setTo("ciberxtrem@gmail.com")
            ->addTo("ciberxtrem@gmail.com")
            ->addTo("marioquesada85@gmail.com")
            //->addTo("amkribo@gmail.com")
            ->setBody("De: ".$email."\nMensaje: \n".$message);

            $this->get('mailer')->send($message);
            //End Sending email
        }
        //return $this->redirect($this->generateUrl("TKShopFrontendIndex"));
        return array();
        
    }
    
    /**
     * @Route("/enviar_consulta_hosting", name="TKShopFrontendContactSendEmailHosting")
     * @Method("post")
     * @template("TecnokeyShopBundle:Default:contacto.html.twig")
     */
    public function sendEmailTestAction(){
        
        $request = Request::createFromGlobals();
        
        //Get request parameters
        $company = $request->request->get("company");
        $email = $request->request->get("email");
        $message = $request->request->get("message");

        
        $para      = 'ciberxtrem@gmail.com';
        $titulo = 'El título';
        $mensaje = $message;
        $cabeceras = 'From: gdeveloperaccount@gmail.com' . "\r\n" .
            'Reply-To: gdeveloperaccount@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        
        $form_feedback = $this->getFormvalidationFeedback($company, $email, $message);
        $this->get('session')->setFlash('form_feedback', $form_feedback);
        if($form_feedback["success"] != NULL){
            mail($para, $titulo, $mensaje, $cabeceras);
        }        
        //return $this->redirect($this->generateUrl("TKShopFrontendIndex"));
        return array();
    }
    
    public function getFormvalidationFeedback($company, $email, $message){
        $errors =  array("title" => "¡Formulario incompleto! Rellene todos los campos antes de enviar.");
        $error_fields = array();
        $success =  "¡Su mensaje ha sido enviado con éxito!";
        
        if($company == "Empresa" || $company == ""){
            $error_fields["Empresa"] = "Falta información de empresa";
        }

        if($email == "" || $email == "Correo"){
            $error_fields["Correo"] = "Falta información de email";
        }
        
        if($message == "Mensaje" || $message == ""){
            $error_fields["Mensaje"] = "Falta información de mensaje";
        }

        if(count($error_fields) > 0) {
            return array(
            "success" => NULL,
            "error" => "¡Formulario incompleto! Rellene todos los campos antes de enviar.",
            "fields" => $error_fields
            );
        }
        else{
            return array(
             "success" => "¡Su mensaje ha sido enviado con éxito!",
            );
        }
    }
}
