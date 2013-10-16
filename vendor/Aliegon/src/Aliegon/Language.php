<?php
namespace Aliegon;

/**
 * Language file.
 *
 * <code>
 * $session = Session::getInstance();
 * $session->myVar = 'my value';
 * echo $session->myVar;
 * </code>
 */
final class Language {

    private $lang = array();
    private $language;
    private $session;
    private $spyc;
    private $config;

    public function __construct(\Aliegon\Config $config, \Aliegon\Spyc $spyc)
    {
        $this->config = $config;
        $this->spyc = $spyc;
        if(isset($_GET['lang']) && $this->exists($_GET['lang']))
            $this->language = $_GET['lang'];
        else{
            $this->language = $this->config->get('lang');
        }
        $this->setLanguage();
    }


    public function get($key)
    {
        $key = strtolower($key);
        $langVar = $this->getArray($key);
        if ($langVar) {
            return $langVar;
        } else {
            return '<span style="color: #00CD00">' . $key . '</span><span style="color: red">' . $this->language . '</span>';
        }
    }

    private function getArray($key)
    {
       $matches = preg_split('/(\.)/', $key );
        
        $variable = $this->lang;

        foreach($matches as $var) {
            if(array_key_exists($var, $variable)) {
                $variable = $variable[$var];
            }
            else
                return false;
        }

        return $variable;
    }

    private function exists($lang)
    {
        return file_exists('./app/language/' . $lang . '/main.yaml');
    }
    
    private function setLanguage()
    {
        if(!$this->exists($this->language)){
            if(!$this->config->has('lang')){
                return false;
            }
            else
                $lang = $this->config->get('lang');
        }
        else
            $lang = $this->language;

        $language = $this->spyc->loadLanguageFile(ROOT . '/app/Language/' . $lang . '/main.yaml');
        $this->lang = $language;
        //$this->session->lang = $lang;
    }

}