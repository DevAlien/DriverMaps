<?php
/**
 * The kernel is in charge of the system, it loads the components and prepare the Dependency Injection Container
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.com (C) 2008-2012
 * @license LGPL(V3) http://www.opensource.org/licenses/lgpl-3.0.html
 * @version 1.0
 */

namespace Aliegon;

use Aliegon\DependencyInjection\DIContainer;
use Aliegon\Config;
use Aliegon\Session\Session;
use Aliegon\Auth\Auth;
use Aliegon\Spyc;
use Aliegon\Language;
use Aliegon\Router\Router;
use Aliegon\Loader;
use Aliegon\Logger\KLogger;
use Aliegon\Request\Request;
use Aliegon\Cacher\PHPCacher;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * The kernel is in charge of the system, it loads the components and prepare the Dependency Injection Container
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.com (C) 2008-2012
 * @version 1.0
 */
class Kernel {
    /**
     * Debug flag
     *
     * @var boolean
     */
    private $debug;

    private $log;

    /**
     * DIContainer object which will contain all the components
     *
     * @var DIContainer
     */
    private $container;
    
    /**
     * Runs the system
     *
     * @param boolean $debug if we are in debug mod or not.
     */
    public function __construct($debug = false){
        $this->log = new KLogger(ROOT . '/log/', KLogger::INFO);
        
        $this->debug = (Boolean) $debug;

        $this->init();
    }
    
    public function getContainer() {
        return $this->container;
    }
    /**
     * starts the loading system
     *
     * @return void
     */
    private function init(){
        $this->boot();
    }

    /**
     * Boots the initialization of the components
     *
     * @return void
     */
    private function boot(){
        $this->initializeDIContainer();
        $this->initializeBasicComponents();
        
    }

    /**
     * Initialize the Dependency Injection Container
     *
     * @return void
     */
    private function initializeDIContainer() {
        $this->container = new DIContainer();
        $this->container->set('logger', $this->log);
        $this->container->set('kernel', $this);
        $this->container->set('yaml', function ($c) { return new Spyc(); });
    }

    /**
     * Loads the basic components into the Container
     *
     * @return void
     */
    private function initializeBasicComponents(){
        $this->initializeConfig();
        $this->loadDoctrine();
        $this->container->set('request', $this->container->share(function ($c) { return new Request($c->get('logger')); }));
        $this->container->set('session', $this->container->share(function ($c) { return new Session($c->get('config')); }));
        $this->container->set('auth', $this->container->share(function ($c) { return new Auth($c->get('config'), $c->get('session')); }));
        $this->container->set('language', $this->container->share(function ($c) { return new Language($c->get('config'), $c->get('yaml')); }));
        $this->container->set('router', $this->container->share(function ($c) { return new Router($c->get('config')->get('base'),$c->get('config')->get('base_dir'), $c->get('auth'), $c->get('logger'), $c->get('request')); }));
        $this->container->set('template', $this->container->share(function ($c) { 
            $template = new Template('maps');
            $template->setLanguage($c->get('language'));
            $template->setConfig($c->get('config'));
            $template->setAuth($c->get('auth'));
            return $template; }));
        $this->container->set('loader', $this->container->share(function ($c) { return new Loader($c); }));
    }

    /**
     * Starts the routing
     *
     * @return void
     */
    private function loadRouting()
    {
        $routings = $this->container->get('phpcacher')->load('routing', $this->container->get('config')->get('routing') . '.routing.yaml');
        $router = $this->container->get('router');
        $router->setRoutings($routings);
        $router->init();
    }

    /**
     * Load Doctrine, if needed
     *
     * @return void
     */
    private function loadDoctrine()
    {
        if(!$this->container->get('config')->has('db_type'))
            return;

        $loader = new \Doctrine\ORM\Proxy\Autoloader();
        $loader->register(ROOT . '/cache/proxies', 'Proxies');

        $this->container->set('em', $this->container->share(function ($c) { 
            $paths = array(ROOT . '/app/Entity');
            $isDevMode = false;

            $dbParams = array(
                'driver'   => 'pdo_' . $c->get('config')->get('db_type'),
                'user'     => $c->get('config')->get('db_username'),
                'password' => $c->get('config')->get('db_password'),
                'dbname'   => $c->get('config')->get('db_name'),
                'host'     => $c->get('config')->get('db_host')
            );

            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
            $config->addEntityNamespace($c->get('config')->get('db_namespace'), 'app\Entity');
            $cache = new \Doctrine\Common\Cache\ArrayCache;

            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);
            $logger = new \Doctrine\DBAL\Logging\EchoSQLLogger();
            //$config->setSQLLogger($logger);
            $config->setProxyDir(ROOT . '/cache/proxies');
            $config->setProxyNamespace('Proxies');
            $config->setAutoGenerateProxyClasses(true);
            return EntityManager::create($dbParams, $config);
        }
        ));
    }
    /**
     * Starts the Loader and loads the right object in base of the routing
     *
     * @return void
     */
    private function initializeConfig()
    {
        $this->container->set('phpcacher', $this->container->share(function ($c) { return new PHPCacher($c->get('logger'), $c->get('yaml')); }));
        $this->container->set('config.fileLocation', 'app/config/config.yaml');
        $this->container->set('config.parsed', $this->container->get('yaml')->loadConfigFile($this->container->get('config.fileLocation')));
        $this->container->set('config', $this->container->share(function ($c) { return new Config($c->get('config.parsed')); }));
    }


    /**
     * Starts the Loader and loads the right object in base of the routing
     *
     * @return void
     */
    public function run(){
        $this->loadRouting();
        $this->container->get('loader')->run();
    }

} 