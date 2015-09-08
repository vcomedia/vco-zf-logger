<?php
namespace VcoZfLogger\Log;

use Zend\Log\Logger as ZendLogger;

/**
 * Class Logger
 * @package VcoZfLogger\Log
 */
class Logger extends ZendLogger
{
    /**
     * @var array
     */
    private $defaultExtra = array();
    
    public function __construct($options = null)
    {
        $defaultExtra = array(
            'sessionId' => session_id(),
            'host'      => !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI',
            'ip'        => !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unavailable'
        );
        $this->setDefaultExtra($defaultExtra);
        
        parent::__construct($options);
    }

    /**
     * @param int   $priority
     * @param mixed $message
     * @param array $extra
     *
     * @return ZendLogger
     */
    final public function log($priority, $message, $extra = array())
    {
        return parent::log($priority, $message, array_merge($extra, $this->defaultExtra));
    }

    /**
     * @param array $defaultExtra
     *
     * @return Logger
     */
    public function setDefaultExtra(array $defaultExtra)
    {
        $this->defaultExtra = $defaultExtra;

        return $this;
    }

    /**
     * @param array $defaultExtra
     *
     * @return Logger
     */
    public function addDefaultExtra(array $defaultExtra)
    {
        $this->defaultExtra[] = $defaultExtra;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultExtra()
    {
        return $this->defaultExtra;
    }
}
