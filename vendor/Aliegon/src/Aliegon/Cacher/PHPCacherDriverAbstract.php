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


/**
 * Template engine which is the View part of the system
 *
 * With the template engine we can assign variables, objects, arrays etc.
 * The files are "compiled" in PHP and if possible, stored in a cache and the include them.
 */
abstract class PHPCacherDriverAbstract {

    protected $yamlParser;

    abstract public function load($file = false);
    abstract public function getData();
    abstract protected function removeOldCachedFiles();

    protected function saveArrayIntoFile($array, $directory, $fileName)
    {
        if (is_writable($directory)) {
            $fp = fopen ( $fileName, 'w' );
            fwrite( $fp, $parsed_string = "<?php" . "\n" . "\$cached = " . var_export( $array, TRUE ) . ";" . "\n?>", strlen( $parsed_string ) );
            fclose( $fp );
        }
    }

    protected function loadCachedArray($file, $type)
    {
        $time = filemtime($file);
        $configfile = ROOT . '/cache/' . $type . '_' . $time . '.php';
        if (file_exists($configfile)) {
            include_once $configfile;
            return $cached;
        }
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