<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Tecnokey\ShopBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;

/**
 * Description of PriceToPrettyPriceTransformer
 *
 * @author mqmtech
 */
class SavePreviousPage {
 
        /**
         * @var \Symfony\Component\DependencyInjection\ContainerInterface
         */
        private $router;
 
        public function __construct(Router $router) {
            $this->router = $router;
        }
 
        public function onKernelRequest(GetResponseEvent $event) {
            
            if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
                return;
            }
 
            /** @var \Symfony\Component\HttpFoundation\Request $request  */
            $request = $event->getRequest();
            /** @var \Symfony\Component\HttpFoundation\Session $session  */
            $session = $request->getSession();
 
            $routeParams = $this->router->match($request->getPathInfo());
            $routeName = $routeParams['_route'];
            if ($routeName[0] == '_') {
                return;
            }
            unset($routeParams['_route']);
            $routeData = array('name' => $routeName, 'params' => $routeParams);
 
            //Skipping duplicates
            $thisRoute = $session->get('this_route', array());
            if ($thisRoute == $routeData) {
                return;
            }
            $session->set('last_route', $thisRoute);
            $session->set('this_route', $routeData);
            $session->save();
        }
}

?>
