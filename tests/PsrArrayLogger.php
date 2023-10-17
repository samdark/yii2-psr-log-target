<?php
declare(strict_types=1);

namespace samdark\log\tests;

use Psr\Log\AbstractLogger;

/**
 * PsrArrayLogger is PSR-3 compatible logger which stores messages
 * in an array. Used for testing only.
 */
class PsrArrayLogger extends AbstractLogger
{
    public array $logs = [];


    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->logs[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    }
}
