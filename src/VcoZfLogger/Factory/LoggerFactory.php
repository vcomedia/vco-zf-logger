<?php
namespace VcoZfLogger\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use VcoZfLogger\Log\Logger;

class LoggerFactory implements FactoryInterface {

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator            
     *
     * @return mixed
     */
    public function createService (ServiceLocatorInterface $serviceLocator) {
        //$realServiceLocator = $serviceLocator->getServiceLocator();
        $config = $serviceLocator->get('Config');

        $config = (isset($config['VcoZfLogger']) && is_array($config['VcoZfLogger'])) ? $config['VcoZfLogger'] : null; 
        //TODO: inject transport and mongo config here
        die(print_r($config));  
        $logger = new Logger($config);
        return $logger;
    }
}