<?php

namespace Tecnokey\ShopBundle\Controller\Developer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Developer controller.
 *
 * @Route("/developer")
 */
class WebConsoleController extends Controller
{
    /**
     * @Route("/", name="TKShopDeveloperIndex")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createExeForm();
        return array('form' => $form->createView());
    }
    
    /**
     * @Route("/exec.{_format}", name="TKShopDeveloperExec", defaults={"_format" = "html"})
     */
    public function execAction($_format)
    {
        $form = $this->createExeForm();

        $request = $this->get('request');
        $input = NULL;
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $input = $data['input'];
                
               $commands = $this->get('session')->get('console.commands');
                if($input == 'clear'){
                   if(is_array($commands)){
                        $commands = array();                        
                   }
                }
                
                $command = array(
                    'input' => $input,
                    'output' => array()
                );
                exec('cd ../; ' . $input, $command['output']);
                
                $commands[] = $command;
                //$output = ($oldOutput == NULL) ? $output : array_merge($oldOutput, $output); 
                //$this->get('session')->set('console.output', $output);
                $this->get('session')->set('console.commands', $commands);
                $this->get('session')->save();
                
                //$this->get('session')->setFlash('output', $output);
            }
        }
            return $this->render("TecnokeyShopBundle:Developer\WebConsole:index.".$_format.".twig", array(
                'form' => $form->createView()
                )
            );
        //return array('form' => $form->createView());
    }
     
    public function createExeForm($defaultinput = ""){
        
        $arr = array(
        'input' => $defaultinput);
        
        $form = $this->createFormBuilder($arr)
            ->add('input', 'text')
            ->getForm();
            
        return $form;
    }
}
