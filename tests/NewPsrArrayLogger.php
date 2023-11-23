<?php
namespace samdark\log\tests;

use Psr\Log\AbstractLogger;

class NewPsrArrayLogger extends AbstractLogger
{
    public array $logs = [];

    /**
     * @inheritdoc
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->logs[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    }
}
