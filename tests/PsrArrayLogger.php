<?php
namespace samdark\log\tests;

use Psr\Log\AbstractLogger;

/**
 * PsrArrayLogger is PSR-3 compatible logger which stores messages
 * in an array. Used for testing only.
 */
class PsrArrayLogger extends AbstractLogger
{
    public $logs = [];

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = array())
    {
        $this->logs[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    }
}
