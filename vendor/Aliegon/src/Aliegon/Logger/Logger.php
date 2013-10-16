<?php
namespace Aliegon\Logger;

use Aliegon\Logger\KLogger;
class Logger
{

    private $loggers = array();

    private $defaultDir = ROOT . '/log/';

    public function __construct($default_dir)
    {
        $this->default_dir = $default_dir;
    }

    public function get($name)
    {
        if(!array_key_exists($name, $this->loggers))
            throw new LoggerDoesNotExistsLoggerException('This logger does not exists, please select an existing one or set a new one');
        
        return $this->loggers[$name];
    }

    public function set($name, $severity = self::INFO, $directory = false)
    {
        if(array_key_exists($name, $this->loggers))
            throw new LoggerAlreadyExistsLoggerException('This logger already exists, you should get it and use it.');

        if($directory == false)
            $directory = $defaultDir;

        $this->loggers[$name] = new KLogger($directory, $severity, $name);

        return $this->loggers[$name]
    }
}