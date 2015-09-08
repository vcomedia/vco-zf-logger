<?php
/**
 * VcoZfLogger - Zend Framework 2 logger module.
 *
 * @category Module
 * @package  VcoZfLogger
 * @author   Vahag Dudukgian (valeeum)
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     http://github.com/vcomedia/vco-zf-logger/
 */

namespace VcoZfLogger;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\EventManager\EventInterface;

/**
 * Class Module
 *
 * @see ConfigProviderInterface
 * @see ViewHelperProviderInterface
 * @package VcoZfLogger
 */

class Module implements ConfigProviderInterface, ServiceProviderInterface, BootstrapListenerInterface, AutoloaderProviderInterface {

    
    public function onBootstrap (EventInterface $e) {
        /**
         * Log any Uncaught Exceptions, including all Exceptions in the stack
          */
        $sharedManager = $e->getApplication()
            ->getEventManager()
            ->getSharedManager();
        $sm = $e->getApplication()->getServiceManager();
        
        $config = $sm->get('Config');
        if(isset($config['VcoZfLogger']) 
            && isset($config['VcoZfLogger']['exceptionhandler']) 
            && $config['VcoZfLogger']['exceptionhandler'] === true
        ) { 
            $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error', 
                function  ($e) use( $sm) {
                    if ($e->getParam('exception')) {
                        $ex = $e->getParam('exception');
                        do {
                            $sm->get('VcoZfLogger')
                                ->crit(
                                sprintf("%s:%d %s (%d) [%s]", $ex->getFile(), 
                                    $ex->getLine(), $ex->getMessage(), 
                                    $ex->getCode(), get_class($ex)));
                        } while ($ex = $ex->getPrevious());
                    }
                });  
        }
    }    
     
    /**
     * @return array
     */
    public function getConfig () {
        return require __DIR__ . '/config/module.config.php';
    }

    //TODO: remove following method and autoload_classmap.php file
    public function getAutoloaderConfig () {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

     /** @return array */
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'VcoZfLogger' => 'VcoZfLogger\Factory\LoggerFactory'
            )
        );
    }
}
