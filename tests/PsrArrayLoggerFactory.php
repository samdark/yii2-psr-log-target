<?php
namespace samdark\log\tests;

use Psr\Log\AbstractLogger;

class PsrArrayLoggerFactory
{
    /**
     * @return AbstractLogger
     */
    public static function getInstance()
    {
        if (version_compare(PHP_VERSION, '8.0', '<')) {
            return new PsrArrayLogger();
        } else {
            return new NewPsrArrayLogger();
        }
    }
}

