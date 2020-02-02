<?php
/**
 * Copyright © Above The Fray Design, Inc. All rights reserved.
 * See ATF_COPYING.txt for license details.
 */

namespace ATF\Common;

use \Zend\Log\Writer\Stream as ZendWriterStream;
use \Zend\Log\Logger as ZendLogger;

class Logger
{
    public static function log($message, $level = 1, $file = '/var/log/atf.log')
    {
        $message = is_array($message) ? json_encode($message) : $message;
        self::logger($message, $level, $file);
        $debugBackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        self::logger($debugBackTrace[0]['file'] . ':' . $debugBackTrace[0]['line'], $level, $file);
    }

    public static function trace($file = '/var/log/atf-trace.log')
    {
        $debugBackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        self::logger('--- START TRACE:', 1, $file);
        foreach ($debugBackTrace as $item) {
            self::logger(@$item['class'] . @$item['type'] . @$item['function'] . ' ' . @$item['file'] . ':' . @$item['line'], 1, '/var/log/atf-trace.log');
        }
        self::logger('--- END TRACE:', 1, $file);
    }

    private static function logger($message, $level = 1, $file = '/var/log/atf.log')
    {
        $writer = new ZendWriterStream(BP . $file);
        $logger = new ZendLogger();
        $logger->addWriter($writer);
        $logger->log($level, $message);
    }
}
