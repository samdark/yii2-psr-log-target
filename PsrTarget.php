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

    public $levelsMap = [
        Logger::LEVEL_ERROR => LogLevel::ERROR,
        Logger::LEVEL_WARNING => LogLevel::WARNING,
        Logger::LEVEL_INFO => LogLevel::INFO,
        Logger::LEVEL_TRACE => LogLevel::DEBUG,
        Logger::LEVEL_PROFILE => LogLevel::DEBUG,
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
            $this->getLogger()->log($this->levelsMap[$message[1]],
                $message[0],
            [
                'category' => $message[2],
                'memory' => $message[5],
                'trace' => $message[4],
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function setLevels($levels)
    {
        if (is_array($levels)) {
            foreach ($levels as $level) {
                if (!isset($this->levelsMap[$level])) {
                    throw new InvalidConfigException('PsrTarget supports only error, warning, info, trace and profile levels.');
                }
            }
        } else {
            $bitmapValues = array_reduce(array_flip($this->levelsMap),
                function ($carry, $item) {
                    return $carry | $item;
                });
            if (!($bitmapValues & $levels) && $levels !== 0) {
                throw new InvalidConfigException("Incorrect $levels value. PsrTarget supports only error, warning, info, trace and profile levels.");
            }
        }

        parent::setLevels($levels);
    }
}
