<?php

namespace Acme\DemoBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

use Symfony\Component\HttpFoundation\Request;

class DemoExtension extends \Twig_Extension
{
    protected $loader;
    
    
    protected $controller;
    
    protected $container;
    
    /**
     *
     * @var Request
     */
    protected $request;

    public function __construct(FilesystemLoader $loader, $container)
    {
        $this->loader = $loader;
        $this->container = $container;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'code' => new \Twig_Function_Method($this, 'getCode', array('is_safe' => array('html'))),
            'getPath' => new \Twig_Function_Method($this, 'getPath'),
        );
    }

    public function getCode($template)
    {
        $controller = htmlspecialchars($this->getControllerCode(), ENT_QUOTES, 'UTF-8');
        $template = htmlspecialchars($this->getTemplateCode($template), ENT_QUOTES, 'UTF-8');

        // remove the code block
        $template = str_replace('{% set code = code(_self) %}', '', $template);

        return <<<EOF
<p><strong>Controller Code</strong></p>
<pre>$controller</pre>

<p><strong>Template Code</strong></p>
<pre>$template</pre>
EOF;
    }

    protected function getControllerCode()
    {
        $r = new \ReflectionClass($this->controller[0]);
        $m = $r->getMethod($this->controller[1]);

        $code = file($r->getFilename());

        return '    '.$m->getDocComment()."\n".implode('', array_slice($code, $m->getStartline() - 1, $m->getEndLine() - $m->getStartline() + 1));
    }

    protected function getTemplateCode($template)
    {
        return $this->loader->getSource($template->getTemplateName());
    }
    
    /**
     *
     * @param string $str
     * @param Array $arr
     * @return string 
     */
    public function _getPath($str, $arr){
        
        $this->request = $this->container->get("request");
        $router = $this->container->get("router");
        
        $url = $router->generate($str, $arr);
        $baseUrl = $this->request->getBaseUrl();
        
        $lenBase = strlen($baseUrl);
        $lenURL = strlen($url);
        
        $url = substr($url, $lenBase, $lenURL);
        return $url;
    }
    
    /**
     *
     * @param string $str
     * @param Array $arr
     * @return string 
     */
    public function getPath($controllerName, $parameters) {
        $router = $this->container->get("router");
        
        $url = $router->generate($controllerName, $parameters);
        
        return $this->toAppPath($url);
    }
    
    /**
     *
     * @param string $path
     * @return string
     */
    public function toAppPath($path){
        $request = $this->container->get("request");
        $baseUrl = $request->getBaseUrl();
        
        $basePos = strpos($path, $baseUrl);
        if($basePos === false){
            return $path;
        }
        else{
            $lenBase = $basePos + strlen($baseUrl);
        }
        
        $lenPath = strlen($path);
        
        $path = substr($path, $lenBase, $lenPath -1);
        return $path;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'demo';
    }
}
