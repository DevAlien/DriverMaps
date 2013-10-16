<?php
namespace Aliegon\Controller;

use Aliegon\DependencyInjection\Container;
use Aliegon\Model;
use Aliegon\Loader;
use Aliegon\Router;
use Aliegon\Language;
use Aliegon\System;
class Controller extends Container
{

    /**
     * GET parameters
     *
     * @var array
     *
     * @access protected
     */
    protected $params = array();
    

    /**
     * Template
     *
     * @var \Aliegon\Template
     *
     * @access protected
     */
    protected $tpl;

    /**
     * Language
     *
     * @var \Aliegon\Language
     *
     * @access protected
     */
    protected $lang;

    public function __construct(\Aliegon\DependencyInjection\DIContainerInterface $container) {
        $this->setContainer($container);
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function getParam($param) {
        return $this->params[$param];
    }

    public function loadController($class, $action, $params = null, $module = null){
       //$this->get('loader')->loadExternalController($class, $action, $params, $module);
    }

    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Gets a service by id.
     *
     * @param  string $id The service id
     *
     * @return object The service
     */
    public function get($id)
    {
        return $this->container->get($id);
    }


    /**
     * Gets the GET parameters.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Gets the Template.
     *
     * @return \Aliegon\Template
     */
    public function getTpl()
    {
        if(!is_object($this->tpl))
            $this->setTpl($this->get('template'));
        return $this->tpl;
    }

    /**
     * Sets the Template.
     *
     * @param \Aliegon\Template $tpl the tpl
     */
    public function setTpl(\Aliegon\Template $tpl)
    {
        $this->tpl = $tpl;
    }

    /**
     * Gets the Language.
     *
     * @return \Aliegon\Language
     */
    public function getLang()
    {
        if(!is_object($this->lang))
            $this->setLang($this->get('language'));
        return $this->lang;
    }

    /**
     * Sets the Language.
     *
     * @param \Aliegon\Language $lang the lang
     */
    public function setLang(\Aliegon\Language $lang)
    {
        $this->lang = $lang;
    }
}

?>