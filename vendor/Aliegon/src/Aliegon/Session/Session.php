<?php
/**
 * Wraps PHP Sessions with an OO API
 *
 * Wraps PHP Sessions with an OO API and it has the possibility to load different session handlers depending on the given Config
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.com (C) 2008-2012
 * @license LGPL(V3) http://www.opensource.org/licenses/lgpl-3.0.html
 * @version 1.0
 */

namespace Aliegon\Session;

use Aliegon\Config;

/**
 * Wraps PHP Sessions with an OO API
 *
 * Wraps PHP Sessions with an OO API and it has the possibility to load different session handlers depending on the given Config
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.com (C) 2008-2012
 * @version 1.0
 */
class Session {

    /**
     * Config of the system
     *
     * @var Config
     */
    private $config;

    /**
     * Stores the config and starts the session with the given name
     *
     * @param Config $config Config of the system
     * @param string $name Name of the session, default 'PHPSESSID'
     */
    public function __construct(\Aliegon\Config $config, $name = 'PHPSESSID')
    {
        $this->config = $config;
        $this->startSession($name);
    }

    /**
     * Magic Method to get a key from the session
     *
     * @param string $key key of the $_SESSION
     */
    public function __get($key)
    {
        $this->get($key);
    }

    /**
     * Magic method to set a session variable
     *
     * @param string $key The key of the array which where we want to store our data
     * @param mixed $value the data that we want to store
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Get a key from the session
     *
     * @param string $key key of the $_SESSION
     */
    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    /**
     * Set a session variable
     *
     * @param string $key The key of the array which where we want to store our data
     * @param mixed $value the data that we want to store
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function destroy($key)
    {
        unset($_SESSION[$key]);
    }
    /**
     * Loads the selected Session Handler from the config and starts the actual session
     *
     * @param string $name Name of the session
     */
    private function startSession($name) {
        if (!isset($_SESSION)) {
            if (headers_sent()) {
                throw new \Exception('headers already sent by');
            }
            if ($this->config->has('sessions')) {
                $session = $this->config->get('session');
                if (array_key_exists('type', $session)) {
                    $sessionClass = 'Aliegon\\Session\\Handler\\' . ucfirst($session['type']) . 'SessionHandler';
                    $sessionHandler = new $sessionClass($this->getConfig());

                    session_set_save_handler(array(&$sessionHandler, "open"), array(&$sessionHandler, "close"), array(&$sessionHandler, "read"), array(&$sessionHandler, "write"), array(&$sessionHandler, "destroy"), array(&$sessionHandler, "gc"));
                }
                if(array_key_exists('lifetime', $session))
                    ini_set("session.gc_maxlifetime", $session['maxlifetime']);
            }
            session_name($name);
            session_start();
        }   
    }

    /**
     * Gets the Config of the system.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the Config of the system.
     *
     * @param Config $config the config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }
}