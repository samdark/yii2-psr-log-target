<?php
namespace samdark\log;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use yii\base\InvalidConfigException;
use yii\log\Logger;
use yii\log\Target;

/**
 * PsrTarget is a log target which passes messages to PSR-3 compatible logger.
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class PsrTarget extends Target implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $_psrLevels = [
        Logger::LEVEL_ERROR => LogLevel::ERROR,
        Logger::LEVEL_WARNING => LogLevel::WARNING,
        Logger::LEVEL_INFO => LogLevel::INFO,
        Logger::LEVEL_TRACE => LogLevel::DEBUG,
        Logger::LEVEL_PROFILE => LogLevel::DEBUG,
        Logger::LEVEL_PROFILE_BEGIN => LogLevel::DEBUG,
        Logger::LEVEL_PROFILE_END => LogLevel::DEBUG,
    ];

    /**
     * @return LoggerInterface
     * @throws InvalidConfigException
     */
    public function getLogger()
    {
        if ($this->logger === null) {
            throw new InvalidConfigException('Logger should be configured with Psr\Log\LoggerInterface.');
        }
        return $this->logger;
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        foreach ($this->messages as $message) {
            $this->getLogger()->log($this->_psrLevels[$message[1]],
                $message[0],
            [
                'category' => $message[2],
                'memory' => $message[5],
                'trace' => $message[4],
            ]);
        }
    }
}
