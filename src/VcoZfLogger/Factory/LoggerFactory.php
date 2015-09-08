<?php
namespace VcoZfLogger\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use VcoZfLogger\Log\Logger;

class LoggerFactory implements FactoryInterface {

    /**
     * @var  VcoZfLogger\Log\Logger
     */
    protected $logger;
    
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator            
     *
     * @return mixed
     */
    public function createService (ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Config');
        $config = $this->configuration($config, $serviceLocator);
        
        $this->logger = new Logger($config);
        $this->execute();
        
        return $this->logger;
    }
    
    /**
     * @param array $config
     * @return array $config
     */
    protected function configuration(array $config, ServiceLocatorInterface $serviceLocator)
    {
        $config = (isset($config['VcoZfLogger']) && is_array($config['VcoZfLogger'])) ? $config['VcoZfLogger'] : array(); 
        
        if (isset($config['writer_plugin_manager'])
            && is_string($config['writer_plugin_manager'])
            && $serviceLocator->has($config['writer_plugin_manager'])
        ) {
            $config['writer_plugin_manager'] = $serviceLocator->get($config['writer_plugin_manager']);
        }

        if ((!isset($config['writer_plugin_manager'])
            || ! $config['writer_plugin_manager'] instanceof AbstractPluginManager)
            && $serviceLocator->has('LogWriterManager')
        ) {
            $config['writer_plugin_manager'] = $serviceLocator->get('LogWriterManager');
        }

        if (isset($config['processor_plugin_manager'])
            && is_string($config['processor_plugin_manager'])
            && $serviceLocator->has($config['processor_plugin_manager'])
        ) {
            $config['processor_plugin_manager'] = $serviceLocator->get($config['processor_plugin_manager']);
        }

        if ((!isset($config['processor_plugin_manager'])
            || ! $config['processor_plugin_manager'] instanceof AbstractPluginManager)
            && $serviceLocator->has('LogProcessorManager')
        ) {
            $config['processor_plugin_manager'] = $serviceLocator->get('LogProcessorManager');
        }
        
        foreach ($config['writers'] as $index => $writerConfig) {
            //inject db
            if (isset($writerConfig['options']['db'])
                && is_string($writerConfig['options']['db'])
                && $serviceLocator->has($writerConfig['options']['db'])
            ) {
                $config['writers'][$index]['options']['db'] = $serviceLocator->get($writerConfig['options']['db']);
            }
            
            //inject mail transport
            if (in_array($writerConfig['name'], array('mail', 'Zend\Log\Writer\Mail'))
                && isset($writerConfig['options']['transport']) 
                && is_string($writerConfig['options']['transport'])
                && $serviceLocator->has($writerConfig['options']['transport'])
            ){
                $config['writers'][$index]['options']['transport'] = $serviceLocator->get($writerConfig['options']['transport']);
            }

            //TODO: inject mongo config
            if (in_array($writerConfig['name'], array('mongodb', 'Zend\Log\Writer\MongoDB'))
                && isset($writerConfig['options']['mongo']) 
                && is_string($writerConfig['options']['mongo'])
                && $serviceLocator->has($writerConfig['options']['mongo'])
            ){
                die(print_r($serviceLocator->get($writerConfig['options']['mongo'])->getConnection()->getMongo()->getServer()));
                $config['writers'][$index]['options']['mongo'] = $serviceLocator->get($writerConfig['options']['mongo'])->getConnection()->getMongo();
            }            
        } 
        
        return $config;
    }
    
    /**
     * @return Logger
     */
    private function execute()
    {
        if ($this->logger->getWriters()->count() == 0) {
            return $this->logger->addWriter(new \Zend\Log\Writer\Null);
        }
    }
}