<?php
/**
 * The router
 *
 * The router will take the URL and will make sure to give the right route to the requested page.
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.com (C) 2008-2012
 * @license LGPL(V3) http://www.opensource.org/licenses/lgpl-3.0.html
 * @version 1.0
 */

namespace Aliegon\Router;

use Aliegon\Router\Exception\MissingControllerArgumentRouterException;
use Aliegon\Router\Exception\MissingActionArgumentRouterException;
use Aliegon\Router\Exception\NoRoutingFoundRouterException;

/**
 * The router
 *
 * The router will take the URL and will make sure to give the right route to the requested page.
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.com (C) 2008-2012
 * @version 1.0
 */
class Router {
    /**
     * The selected controller after that the router processed our route
     *
     * @var string
     */
    public $controller;

    /**
     * The selected module after that the router processed our route
     *
     * @var string
     */
    public $module;

    /**
     * The selected action after that the router processed our route
     *
     * @var string
     */
    public $action;

    /**
     * The GET parameters parsed in the route, you can add them using ":name" in the routing file
     *
     * @var array
     */
    public $params;

    /**
     * Contains all the rules extracted from the routing file
     *
     * @var array
     */
    public $rules;

    /**
     * Base of the URL (normally is the domain)
     *
     * @var string
     */
    public $base;

    /**
     * Base directory, if your index.php is in a subfolder you should change it.
     *
     * @var string
     */
    public $basedir;

    private $auth;

    private $log;

    private $request;
    
    /**
     * Set the base, basedir and initialize the array
     *
     * @param string $base The base URL
     * @param string $basedir The base directory where the index is located.
     */
    public function __construct($base, $basedir, $auth, $log, $request)
    {
        $this->base = $base;
        $this->basedir = $basedir;
        $this->rules = array();
        $this->url = $this->getUrl();
        $this->method = $this->getMethod();
        $this->auth = $auth;
        $this->log = $log;
    }

    /**
     * redirects the user to a selected routing
     *
     * @TODO give the possibility to pass a parameters array 
     * @param string $routing the name of the routing where you want to be redirected to
     */
    public function redirect($routing, $params = false, $die = true) {
        if (!array_key_exists($routing, $this->routings))
            throw new NoRoutingFoundRouterException('The Routing "' . $routing . '" was not found. Impossible to redirect');
        
        $url = $this->routings[$routing]['url'];

        if ($params !== false) {
            $urlParams = $this->getUrlParams($url);
            if ($urlParams !== false && count($urlParams) > 0) {
                foreach($urlParams as $urlParam) {
                    if (array_key_exists($urlParam, $params))
                        $url = str_replace(':' . $urlParam, $params[$urlParam], $url);
                    else
                        throw new \Exception('parameter ' . $urlParam . ' is missing');
                }
            }
        }

        header('Location: ' . $this->basedir . $url);
        if($die === true)
            die();
    }

    private function getUrlParams($url)
    {
        preg_match_all('/:(\w+){1,}/', $url, $test);
        return isset($test[1]) ? $test[1] : false;
    }
    /**
     * Add the rules into the rules property to have them already there when it will use them
     *
     * @param array $routings Routings from the routing file
     */
    public function setRoutings($routings) {
        $this->routings = $routings;
        foreach ($routings as $routing)
            $this->addRule($routing['url'], $routing['param']);
    }

    /**
     * Cleans the array removing the empty ones 
     *
     * @param array $array The array to clean
     */
    private function arrayClean($array) {
        foreach ($array as $key => $value) {
            if (strlen($value) == 0)
                unset($array[$key]);
        }
    }

    /**
     * Match the rule with the given url
     *
     * @param string $rule Rule to be checked
     * @param string $data URL to be compared to
     *
     * @return mixed False or Array with the params if it's true 
     */
    private function ruleMatch($rule, $data) {
        if($rule === $data)
            return true;

        $ruleItems = explode('/', $rule);
        $this->arrayClean($ruleItems);
        $dataItems = explode('/', $data);
        $this->arrayClean($dataItems);

        if (count($ruleItems) == count($dataItems)) {
            $result = array();

            foreach ($ruleItems as $ruleKey => $ruleValue) {
                if (preg_match('/^:[\w]{1,}$/', $ruleValue)) {
                    $ruleValue = substr($ruleValue, 1);
                    $result[$ruleValue] = $dataItems[$ruleKey];
                } else {
                    if (strcmp($ruleValue, $dataItems[$ruleKey]) != 0) {
                        return false;
                    }
                }
            }

            if (count($result) > 0)
                return $result;

            unset($result);
        }

        return false;
    }

    /**
     * Easy routing system, will just route you in the path that you give /controller/action/pa/ra/ms
     *
     * @param string $url The URL
     */
    private function defaultRoutes($url) {
        $items = explode('/', $url);

        foreach ($items as $key => $value) {
            if (strlen($value) == 0)
                unset($items[$key]);
        }

        if (count($items)) {
            $this->controller = array_shift($items);
            $this->action = array_shift($items);
            $this->params = $items;
        }
    }

    /**
     * This will process the routing in base of the given routings array and the REQUEST_URI
     *
     * @return boolean True if the route is found or die
     */
    public function init() {
        foreach ($this->rules as $routingKey => $routing) {
            foreach ($routing as $ruleKey => $ruleData) {
                if (isset($ruleData['method']) && strtoupper($ruleData['method']) != $this->method)
                    continue;
                if (isset($ruleData['logged']) && $ruleData['logged'] == true && $this->auth->isLogged() == false)
                    continue;
                else if (isset($ruleData['logged']) && $ruleData['logged'] == false && $this->auth->isLogged() != false)
                    continue;

                $params = $this->ruleMatch($ruleKey, $this->url);
                if ($params) {
                    $this->loadData($ruleData, $params);
                    
                    return true;
                }
            }

        }
        
        die('No Rooting found');
    }

    /**
     * Add a rule in the rules property
     *
     * @param string $rule Name of the routing
     * @param array $target Infos to know where route if the rule is matched
     */
    public function addRule($rule, $target) {
        $this->rules[][$rule] = $target;
    }

    /**
     * Get choosed controller
     *
     * @return string Name of the controller to load
     */
    public function getController() {
        return ucfirst($this->controller) . 'Controller';
    }

    /**
     * Get choosed action
     *
     * @return string Name of the action to load
     */
    public function getAction() {
        return $this->action . 'Action';
    }

    /**
     * Get choosed module
     *
     * @return string Name of the module to load
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * Get found parameters
     *
     * @return array All the parameters found
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Get one parameter by key
     *
     * @return string value of the choosen parameter
     */
    public function getParam($key) {
        return $this->params[$key];
    }
 
    private function getUrl() {
        $url = ($this->startsWith($_SERVER['REQUEST_URI'], $this->basedir)) ? substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($this->basedir)) : $_SERVER['REQUEST_URI'];

        $realURL = explode('?', $url);
        return $realURL[0];
    }
 
    private function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    private function loadData($ruleData, $params) {
        $this->checkRuleData($ruleData);

        $this->controller = $ruleData['controller'];
        $this->action = $ruleData['action'];
        $this->module = (array_key_exists('module', $ruleData) ? $ruleData['module'] : null);
        $this->params = ($params === true) ? array() : $params;

    }

    private function checkRuleData($ruleData) {
        if(!array_key_exists('controller', $ruleData))
            throw new MissingControllerArgumentRouterException('Missing Controller argument in your routing file');

        if(!array_key_exists('action', $ruleData))
            throw new MissingActionArgumentRouterException('Missing Action argument in your routing file');
    }
    private function startsWith($haystack, $needle) {
        
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }
}