<?php
namespace Log;

use SeasLog;

class Log
{
    protected $_module = 'default';

    public function __construct($path = LOG_PATH, $module = '')
    {
        $this->setBasePath($path);
        $this->setAPI($module);
    }

    public function setBasePath($path = LOG_PATH)
    {
        SeasLog::setBasePath($path);
    }

    public function setAPI($module)
    {
        if ($module) {
            $this->_module = $module;
        }
    }

    public function error($message, $content = array())
    {
        SeasLog::error($this->_toString($message), $content, $this->_module);
    }

    public function info($message, $content = array())
    {
        SeasLog::info($this->_toString($message), $content, $this->_module);
    }

    public function warning($message, $content = array())
    {
        SeasLog::warning($this->_toString($message), $content, $this->_module);
    }

    protected function _toString($message)
    {
        if (is_object($message) || is_array($message)) {
            return print_r($message, TRUE);
        } else {
            return $message;
        }
    }
}
