<?php

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpFoundation\Request;

//$kernel = new AppKernel('prod', false); //FALSE = WITH CACHE ACTIVATED
$kernel = new AppKernel('prod', true); // TRUE = WITH NO CACHE
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$kernel->handle(Request::createFromGlobals())->send();
