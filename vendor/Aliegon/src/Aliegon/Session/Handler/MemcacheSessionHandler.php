<?php

namespace Aliegon\Session\Handler;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of memcache
 *
 * @author GonUltra
 */
class MemcacheSessionHandler {

    public $lifeTime;
    public $memcache;
    public $initSessionData;

    public function __construct(\Aliegon\Config $config) {
        register_shutdown_function("session_write_close");
        $this->memcache = new \Memcache;
        $this->lifeTime = intval(ini_get("session.gc_maxlifetime"));
        $this->initSessionData = null;
        $sessions = $config->get('sessions');
        foreach ($sessions['servers'] as $ip => $port)
            $this->memcache->addserver($ip, $port);
        return true;
    }

    /**
     * Re-initialize existing session, or creates a new one. Called when a session starts or when session_start() is invoked.
     *
     * @param string $savePath The path where to store/retrieve the session
     * @param string $sessionName The session id
     */
    public function open($savePath, $sessionName) {
        $sessionID = session_id();
        if ($sessionID !== "") {
            $this->initSessionData = $this->read($sessionID);
        }

        return true;
    }

    public function close() {
        $this->lifeTime = null;
        $this->memcache = null;
        $this->initSessionData = null;

        return true;
    }

    public function read($sessionID) {
        $data = $this->memcache->get($sessionID);
        return $data;
    }

    public function write($sessionID, $data) {
        $result = $this->memcache->set($sessionID, $data, false, $this->lifeTime);

        return $result;
    }

    public function destroy($sessionID) {
        $this->memcache->delete($sessionID);

        return true;
    }

    public function gc($maxlifetime) {
        return true;
    }

}