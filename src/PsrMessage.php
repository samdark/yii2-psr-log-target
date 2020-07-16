<?php

namespace samdark\log;

/**
 * Data transfer object implementation for advanced PSR3 message and context handling
 *
 * @author Maksim Rodikov <maxrodikov@gmail.com>
 * @package samdark\log
 */
final class PsrMessage
{
    /** @var string */
    private $message;

    /** @var array */
    private $context = [];

    public function __construct($message, $context = [])
    {
        $this->message = (string) $message;
        $this->context = is_array($context) ? $context : [$context];
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * PSR3 compatibility
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

}