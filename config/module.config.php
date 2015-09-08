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
        'writers' => array()
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