<?php
/**
 * Template engine which is the View part of the system
 *
 * With the template engine we can assign variables, objects, arrays etc.
 * The files are "compiled" in PHP and if possible, stored in a cache and the include them.
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright AneCMS.com (C) 2008-2012
 * @license LGPL(V3) http://www.opensource.org/licenses/lgpl-3.0.html
 * @version 1.0
 */
namespace Aliegon\Cacher;

use Aliegon\Config;
use Aliegon\Language;

/**
 * Template engine which is the View part of the system
 *
 * With the template engine we can assign variables, objects, arrays etc.
 * The files are "compiled" in PHP and if possible, stored in a cache and the include them.
 */
class PHPCacher {

    private $log;

    private $yamlParser;

    public function __construct($log, $yamlParser)
    {
        $this->log = $log;
        $this->yamlParser = $yamlParser;
    }

    public function load($type, $file = false)
    {
        try {
            $cacherDriver = '\\Aliegon\\Cacher\\Driver\\' . ucfirst($type) . 'PHPCacher';
            $cacher = new $cacherDriver();
            $cacher->load($file);
            $cacher->setYamlParser($this->getYamlParser());

            return $cacher->getData();
        }
        catch ( \Exception $e) {

        }
    }
    
    private function isFileCached($file)
    {
        if (file_exists($file))
            return true;

        return false;
    }

    private function getCachedFile($file)
    {
        if(file_exists($file)){
            include_once $file;
            return $cached;
        }
        else
            $this->getLog()->info('Could not find the cached file: ' . $file);

        return false;
    }

    public function setYamlParser($yamlParser)
    {
        $this->yamlParser = $yamlParser;
    }

    public function getYamlParser()
    {
        return $this->yamlParser;
    }
}