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
    private $customExtra = array();

    /**
     * @param int   $priority
     * @param mixed $message
     * @param array $extra
     *
     * @return ZendLogger
     */
    final public function log($priority, $message, $extra = array())
    {
        $customExtra = array(
            'Zf2Logger' => array(
                'sessionId' => session_id(),
                'host'      => !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI',
                'ip'        => !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unavailable'
            )
        );

        return parent::log($priority, $message, array_merge($extra, $customExtra, $this->customExtra));
    }

    /**
     * @param array $customExtra
     *
     * @return Logger
     */
    public function setCustomExtra(array $customExtra)
    {
        $this->customExtra = $customExtra;

        return $this;
    }

    /**
     * @param array $customExtra
     *
     * @return Logger
     */
    public function addCustomExtra(array $customExtra)
    {
        $this->customExtra[] = $customExtra;

        return $this;
    }

    /**
     * @return array
     */
    public function getCustomExtra()
    {
        return $this->customExtra;
    }
}
