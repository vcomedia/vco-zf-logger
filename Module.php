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

/**
 * Class Module
 *
 * @see ConfigProviderInterface
 * @see ViewHelperProviderInterface
 * @package VcoZfLogger
 */

class Module implements ConfigProviderInterface, ServiceProviderInterface {

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
            'invokables' => array(
                'VcoZfLogger\Service\MinifyJsService' => 'VcoZfLogger\Service\MinifyJsService',
                'VcoZfLogger\Service\MinifyCssService' => 'VcoZfLogger\Service\MinifyCssService'
            )
        );
    }
}
