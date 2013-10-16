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
namespace Aliegon\Cacher\Driver;

use Aliegon\Cacher\PHPCacherDriverAbstract;

/**
 * Template engine which is the View part of the system
 *
 * With the template engine we can assign variables, objects, arrays etc.
 * The files are "compiled" in PHP and if possible, stored in a cache and the include them.
 */
class RoutingPHPCacher extends PHPCacherDriverAbstract {

    private $type = 'routing';

    private $cacheDir;
    private $scanRoutingsDir;
    private $file;
    private $array = array();

    public function load($file = false)
    {
        $this->cacheDir = ROOT . '/cache/';
        $this->scanRoutingsDir = '/app/Modules';
        $this->file = ROOT . '/app/config/' . $file;
        $this->fileName = $file;
    }

    public function getData()
    {
        if (!$this->isFileRoutingCached()) {
            $this->removeOldCachedFiles();
            $this->cacheRouting();

            return $this->array;
        }

        return $this->loadCachedArray($this->file, $this->type);
    }
    
    protected function removeOldCachedFiles()
    {
        if (is_writable($this->cacheDir))
            if ($files = glob($this->cacheDir . 'routing_*.php'))
                foreach ($files as $delfile)
                    unlink($delfile);
    }

    private function cacheRouting()
    {
        $this->loadRouting();

        $time = filemtime($this->file);
        $configfile = $this->cacheDir . 'routing_' . $time . '.php';

        $this->saveArrayIntoFile($this->array, $this->cacheDir, $configfile);
    }

    private function isFileRoutingCached()
    {
        $time = filemtime($this->file);
        $configfile = $this->cacheDir . 'routing_' . $time . '.php';
        if(file_exists($configfile))
            return true;

        return false;
    }

    private function loadRouting()
    {
        $this->loadBaseRouting();
        $this->loadAllRoutings();
    }
    private function addYamlToArray($file)
    {
        $parsed = $this->getYamlParser()->loadFile($file);
        $this->array = array_merge($this->array, $parsed);
    }

    private function loadBaseRouting()
    {
        $this->addYamlToArray($this->file);
    }

    private function loadAllRoutings()
    {
        if ($modules = glob($this->scanRoutingsDir . '/*'))
            foreach ($modules as $module) {
            if(is_dir($module))
                if(file_exists($module . '/config/' . $this->file))
                    $this->addYamlToArray($module . '/config/' . $this->file);
        }
    }

}