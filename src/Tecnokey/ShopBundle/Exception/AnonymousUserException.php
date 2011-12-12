<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Tecnokey\ShopBundle\Exception;

use \Exception;
/**
 * Description of AnonymousUserException
 *
 * @author mqmtech
 */
class AnonymousUserException extends Exception{
        // Redefinir la excepción, por lo que el mensaje no es opcional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // algo de código
    
        // asegúrese de que todo está asignado apropiadamente
        parent::__construct($message, $code, $previous);
    }

    // representación de cadena personalizada del objeto
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>
