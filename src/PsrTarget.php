<?php
namespace samdark\log;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;
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

    /**
     * @var bool If enabled, logger use original timestamp from buffer
     */
    public $addTimestampToContext = false;

    /**
     * @var array
     */
    private $_levels = [
        Logger::LEVEL_ERROR => LogLevel::ERROR,
        Logger::LEVEL_WARNING => LogLevel::WARNING,
        Logger::LEVEL_INFO => LogLevel::INFO,
        Logger::LEVEL_TRACE => LogLevel::DEBUG,
        Logger::LEVEL_PROFILE => LogLevel::DEBUG,
        Logger::LEVEL_PROFILE_BEGIN => LogLevel::DEBUG,
        Logger::LEVEL_PROFILE_END => LogLevel::DEBUG,

        // Psr Levels
        LogLevel::EMERGENCY => LogLevel::EMERGENCY,
        LogLevel::ALERT => LogLevel::ALERT,
        LogLevel::CRITICAL => LogLevel::CRITICAL,
        LogLevel::ERROR => LogLevel::ERROR,
        LogLevel::WARNING => LogLevel::WARNING,
        LogLevel::NOTICE => LogLevel::NOTICE,
        LogLevel::INFO => LogLevel::INFO,
        LogLevel::DEBUG => LogLevel::DEBUG,
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
            $level = $message[1];
            if (!isset($this->_levels[$level])) {
                continue;
            }

            $context = [];
            if (isset($message[4])) {
                $context['trace'] = $message[4];
            }

            if (isset($message[5])) {
                $context['memory'] = $message[5];
            }

            if (isset($message[2])) {
                $context['category'] = $message[2];
            }

            if ($this->addTimestampToContext && isset($message[3])) {
                $context['timestamp'] = $message[3];
            }

            $text = $message[0];
            if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Throwable || $text instanceof \Exception) {
                    $context['exception'] = $text;
                    $text = (string)$text;
                } else {
                    $text = VarDumper::export($text);
                }
            }

            $this->getLogger()->log($this->_levels[$level], $text, $context);
        }
    }

    /**
     * Sets the message levels that this target is interested in.
     *
     * The parameter can be an array.
     * Valid level names include: 'error',
     * 'warning', 'info', 'trace' and 'profile'; valid level values include:
     * [[Logger::LEVEL_ERROR]], [[Logger::LEVEL_WARNING]], [[Logger::LEVEL_INFO]],
     * [[Logger::LEVEL_TRACE]], [[Logger::LEVEL_PROFILE]] and Psr Log levels:
     * [[LogLevel::EMERGENCY]], [[LogLevel::ALERT]], [[LogLevel::CRITICAL]],
     * [[LogLevel::ERROR]], [[LogLevel::WARNING]], [[LogLevel::NOTICE]],
     * [[LogLevel::INFO]] and [[LogLevel::DEBUG]].
     *
     * For example,
     *
     * ```php
     * ['error', 'warning', LogLevel::CRITICAL, LogLevel::EMERGENCY]
     * ```
     *
     * @param array $levels message levels that this target is interested in.
     * @throws InvalidConfigException if $levels value is not correct.
     */
    public function setLevels($levels)
    {
        static $levelMap = [
            'error' => Logger::LEVEL_ERROR,
            'warning' => Logger::LEVEL_WARNING,
            'info' => Logger::LEVEL_INFO,
            'trace' => Logger::LEVEL_TRACE,
            'profile' => Logger::LEVEL_PROFILE,
        ];

        if (is_array($levels)) {
            $intrestingLevels = [];
            
            foreach ($levels as $level) {
                if (!isset($this->_levels[$level]) && !isset($levelMap[$level])) {
                    throw new InvalidConfigException("Unrecognized level: $level");
                }

                if (isset($levelMap[$level])) {
                    $intrestingLevels[$levelMap[$level]] = $this->_levels[$levelMap[$level]];
                }

                if (isset($this->_levels[$level])) {
                    $intrestingLevels[$level] = $this->_levels[$level];
                }
            }
            
            $this->_levels = $intrestingLevels;
        } else {
            throw new InvalidConfigException("Incorrect $levels value");
        }
    }
}
