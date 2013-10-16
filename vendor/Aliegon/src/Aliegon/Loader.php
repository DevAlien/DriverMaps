<?php

namespace Aliegon;

use Aliegon\Controller;

class Loader {

    private $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function run(){
        $this->loadController($this->container->get('router')->getController(), $this->container->get('router')->getAction(), $this->container->get('router')->getParams(), $this->container->get('router')->getModule());
    }

    public function loadModel($model, $module = null) {
        if ($module !== null)
            if (file_exists(ROOT . '/app/modules/' . $module . '/models/' . $model . 'Model.php'))
                $modelName = '\\app\\modules\\' . $module . '\\models\\' . $model . 'Model';
            else
                die('File for the Model <b>' . $model . '</b> not found.<br />app/modules/' . $module . '/models/' . $model . 'Model.php');
        else
            $modelName = '\\app\\models\\' . $model . 'Model';
        \Aliegon\Model::setToolbox($this->container->get('toolbox'));
        return $mod = new $modelName();
    }

    public function loadController($class, $action, $params = null, $module = null){
        try {
            if ($module !== null)
                $c = '\\app\\Modules\\' . $module . '\\Controller\\' . $class;
            else
                $c = '\\app\\Controller\\' . $class;

            $controller = new $c($this->container);
            $controller->setParams($params);
            call_user_func_array(array($controller, $action), array());
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}