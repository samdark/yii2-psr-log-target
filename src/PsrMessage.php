<?php

namespace samdark\log;

/**
 * Data transfer object implementation for advanced PSR3 message and context handling
 *
 * @author Maksim Rodikov <maxrodikov@gmail.com>
 * @package samdark\log
 */
final class PsrMessage implements \Stringable
{
    private string $message;

    private array $context;

    public function __construct($message, $context = [])
    {
        $this->message = (string)$message;
        $this->context = is_array($context) ? $context : [$context];
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
