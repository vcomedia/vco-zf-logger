## VcoZfLogger - Zend Framework 2 logger module.
Wrapper for Zend/Logger/Log which allows users to track exceptions, add default "extra" attributes, automatic injection of mail transport for the mail writer and automatic mongo client/db for the mongo writer.  

## Installation
### Composer
 * Install [Composer](http://getcomposer.org/doc/00-intro.md)
 * Install the module using Composer into your application's vendor directory. Add the following line to your `composer.json`.

 ```json
 {
    "require": {
        "vcomedia/vco-zf-logger": "dev-master"
    }
 }
```
 * Execute ```composer update```
 * Enable the module in your ZF2 `application.config.php` file.

 ```php
 return array(
     'modules' => array(
         'VcoZfLogger'
     )
 );
 ```
 * Copy and paste the `vco-zf-logger/config/module.vco-zf-logger.local.php.dist` file to your `config/autoload` folder and customize it with your configuration settings. Make sure to remove `.dist` from your file. Your `module.vco-zf-logger.local.php` might look something like the following:

  ```php
 <?php

return array(
    'VcoZfLogger' => array(
        'exceptionhandler' => true,
        'errorhandler' => true, // note: this disables native error handler
        'fatal_error_shutdownfunction' => true,
        'writers' => array(
            'standardstreamwriter' => array(
                'name' => 'stream',
                'options' => array(
                    'stream' => 'data/log/application.log',
                    'filters' => array(
                        'priority' => array(
                            'name' => 'priority',
                            'options' => array(
                                'priority' => \Zend\Log\Logger::WARN
                            )
                        ),
                        'suppress' => array(
                            'name' => 'suppress',
                            'options' => array(
                                'suppress' => false
                            )
                        )
                    ),
                    'formatter' => array(
                        'name' => 'simple',
                        'options' => array(
                            'dateTimeFormat' => 'Y-m-d H:i:s'
                        )
                    )
                )
            ),
            // setup instructions - https://craig.is/writing/chrome-logger
            // chrome plugin - https://chrome.google.com/webstore/detail/chromephp/noaneddfkdjfnfdakjjmocngnfkfehhd
            'chromphpwriter' => array(
                'name' => 'chromephp',
                'options' => array(
                    'filters' => array(
                        'priority' => array(
                            'name' => 'priority',
                            'options' => array(
                                'priority' => \Zend\Log\Logger::DEBUG
                            )
                        ),
                        'suppress' => array(
                            'name' => 'suppress',
                            'options' => array(
                                'suppress' => false
                            )
                        )
                    )
                )
            ),
            'emailwriter' => array(
                'name' => 'mail',
                'options' => array(
                    'mail' => array(
                        'from' => 'from@domain.com',
                        'to' => 'to@domain.com'
                    ),
                    'subject_prepend_text' => 'Application Exception',
                    'transport' => 'mail.transport',  //service name pointing to mail transport
                    'filters' => array(
                        'priority' => array(
                            'name' => 'priority',
                            'options' => array(
                                'priority' => \Zend\Log\Logger::ERR
                            )
                        ),
                        'suppress' => array(
                            'name' => 'suppress',
                            'options' => array(
                                'suppress' => false
                            )
                        )
                    ),
                    'formatter' => array(
                        'name' => 'simple',
                        'options' => array(
                            'dateTimeFormat' => 'Y-m-d H:i:s'
                        )
                    )
                )
            ),
            'mongowriter' => array(
                'name' => 'Zend\Log\Writer\MongoDB',
                'options' => array(
                    'mongo' => 'doctrine.documentmanager.odm_default', //service name pointing to doctrine document manager or standard array to configure mongoClient
                    'database' => null,   //if empty and using service name in above option, default doctrine odm db will be injected
                    'collection' => 'application.log',
                    'save_options' => array(
                        'w' => 0    //fire and forget
                    )
                ),
                'filters' => array(
                    'priority' => array(
                        'name' => 'priority',
                        'options' => array(
                            'priority' => \Zend\Log\Logger::DEBUG
                        )
                    ),
                    'suppress' => array(
                        'name' => 'suppress',
                        'options' => array(
                            'suppress' => true
                        )
                    )
                ),
                'formatter' => array(
                    'name' => 'simple',
                    'options' => array(
                        'dateTimeFormat' => 'Y-m-d H:i:s'
                    )
                )
            )
        )
    )
);

  ```

Note: The configuration array returned by the top level 'VcoZfLogger' key is passed directly into the Log class constructor with the exception of the mail transport and mongo credential injection which are both optional.

* To access your logger and log a message, you can do something like so:

 ```php
$logger = $this->getServiceLocator()->get('VcoZfLogger');
$logger->log(\Zend\Log\Logger::INFO, 'Some message to log.');
 ```
 
* To add additional 'extra' parameters to all logged messages, add the following to onBootstrap method in Module.php:

 ```php
    public function onBootstrap (MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $sm = $e->getApplication()->getServiceManager();
        $logger = $sm->get('VcoZfLogger');
        $logger->addDefaultExtra(array('foo' => 'bar'));
    }
 ```
 
 * Note: The following 'extra' parameters are added by default to all logged messages:

 ```php
         $defaultExtra = array(
            'sessionId' => session_id(),
            'host'      => !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI',
            'ip'        => !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unavailable'
        );
 ```
 
 You can remove default 'extra' options using setDefaultExtra method :
 
  ```php
    public function onBootstrap (MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $sm = $e->getApplication()->getServiceManager();
        $logger = $sm->get('VcoZfLogger');
        $logger->setDefaultExtra(array());
    }
  ```
 

## License
The MIT License (MIT)

Copyright (c) 2015 Vahag Dudukgian

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
