<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserManager
 *
 * @author mqmtech
 */
namespace Tecnokey\ShopBundle\Service;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Tecnokey\ShopBundle\Entity\Shop\User;

class EcommerceMailer {
    
    private $kernel;
    private $mailer;
    
    public function __construct($kernel, $mailer) {
        $this->kernel = $kernel;
        $this->mailer = $mailer;
    }
    
    public function sendEmail($from, $to, $subject, $body){
            $currentEnvironment = $this->getKernel()->getEnvironment();
        
            if(in_array($currentEnvironment, array('dev', 'test'))){
                $this->sendEmailtBySwift($from, $to, $subject, $body);
            }
            else{
                //$this->sendEmailByPHPMail($from, $to, $subject, $body);
                $this->sendEmailByPHPMail($from, 'gdeveloperaccount@gmail.com', $subject, $body);
            }
    }
    
    public function sendEmailtBySwift($from, $to, $subject, $body) {
        
            $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            //->setTo("ciberxtrem@gmail.com")
            ->addTo($to)
            ->addTo("marioquesada85@gmail.com")
            ->setBody($body);

            $this->getMailer()->send($message);
     
    }
    
    public function sendEmailByPHPMail($from, $to, $subject, $body){
        $headers = 'From: '. $from . "\r\n" .
            'Reply-To: '. $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();            
            mail($to, $subject, $body, $headers);
    }
    
    public function getKernel(){
        return $this->kernel;
    }
    
    public function setKernel($kernel){
        $this->kernel = $kernel;
    }
    
    public function getMailer(){
        return $this->mailer;
    }
    
    public function setMailer($mailer){
        $this->mailer = $mailer;
    }
}

?>
