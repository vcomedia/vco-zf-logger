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

return array(
    'VcoZfLogger' => array(
        'exceptionhandler' => true,
        'errorhandler' => true, // this disables native error handler
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
                                'priority' => (APPLICATION_ENV == 'production' ? \Zend\Log\Logger::WARN : \Zend\Log\Logger::DEBUG)
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
            // chrome plugin -
            // https://chrome.google.com/webstore/detail/chromephp/noaneddfkdjfnfdakjjmocngnfkfehhd
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
                                'suppress' => (APPLICATION_ENV == 'production' ? true : false)
                            )
                        )
                    )
                )
            ),
            'emailwriter' => array(
                'name' => 'mail',
                'options' => array(
                    'mail' => array(
                        'from' => 'v@vahag.co',
                        'to' => 'v@vahag.co'
                    ),
                    'subject_prepend_text' => 'Application Exception',
                    'transport' => 'mail.transport',  //service name pointing to transport
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
                    'mongo' => new \MongoClient('mongodb://localhost/'),
                    'database' => 'miller',
                    'collection' => 'applog',
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
        )
    )
);

// //example of custom writer/filter/formatter
// 'customstream' => array(
// 'name' => 'My\Log\Writer\CustomStream',
// 'options' => array(
// 'stream' => '/data/log/my.log',
// 'filters' => array(
// 'priority' => array(
// 'name' => 'My\Log\Filter\Custom',
// 'options' => array()
// )
// ),
// 'formatter' => array(
// 'name' => 'My\Log\Formatter\Custom',
// 'options' => array()
// )
// )
// )