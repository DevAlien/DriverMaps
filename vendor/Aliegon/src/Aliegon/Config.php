<?php
/**
 * Manage the config data, get, check etc
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.Com (C) 2008-2012
 * @license LGPL(V3) http://www.opensource.org/licenses/lgpl-3.0.html
 * @version 1.0
 */

namespace Aliegon;

/**
 * Manage the config data, get, check etc
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.Com (C) 2008-2012
 * @version 1.0
 */
class Config {

    /**
     * Configuration array with all the settings
     *
     * @var array
     */
    private $config;

    /**
     * Chose the right config to use and set it to the config attribute
     *
     * @param array $config Array from the Config file
     */
    public function __construct($config){
        $this->init($config);
    }

    /**
     * Checks if has a key in the selected config
     *
     * @param string $key key that we want to check
     *
     * @return boolean
     */
    public function has($key) {
        return isset($this->config[$key]);
    }
    
    /**
     * Get the value of the given key config
     *
     * @param string $key key of the value that we want to get
     *
     * @return mixed Returns the value of the key
     */
    public function get($key) {
        return $this->config[$key];
    }
    
    /**
     * Get the cache folder in the config but if it doesn't exists will take the generic one ROOT/cache
     *
     * @return string Cache path
     */
    public function getCache(){
        if($this->has('cache')){
            return ROOT . $this->get('cache');
        }
        else
            return ROOT . '/cache';
    }

    /**
     * chose the config file and set it
     *
     * @param array $config config array extracted from the file
     */
    private function init($config){
        $config = $this->chooseConfig($config);
        $this->setConfig($config);
    }
    
    /**
     * set the config array into the property config
     *
     * @param array $config the selected config array
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    /**
     * Actually choose which array should return in base of the HTTP_HOST
     *
     * @param array $config config array extracted from the file
     *
     * @return mixed Returns the selected config array
     */
    private function chooseConfig($config) {
        if (isset($_SERVER['HTTP_HOST']) && array_key_exists($_SERVER['HTTP_HOST'], $config))
            return $config[$_SERVER['HTTP_HOST']];
        else if (array_key_exists('default', $config))
            return $config['default'];
        else
            die('You should write your config file at "/app/config/config.yaml"');
    }

}