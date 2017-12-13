<?php
namespace samdark\log\tests;

use Psr\Log\LogLevel;
use yii\log\Dispatcher;
use yii\log\Logger;

class PsrTargetTest extends \PHPUnit_Framework_TestCase
{
    public function testLogDataProvider()
    {
        $context = [
            'category' => 'application',
            'memory' => 42,
            'trace' => [],
        ];

        return [
            ['error', Logger::LEVEL_ERROR, [
                'level' => LogLevel::ERROR,
                'message' => 'error',
                'context' => $context,
            ]],
            ['warning', Logger::LEVEL_WARNING, [
                'level' => LogLevel::WARNING,
                'message' => 'warning',
                'context' => $context,
            ]],
            ['info', Logger::LEVEL_INFO, [
                'level' => LogLevel::INFO,
                'message' => 'info',
                'context' => $context,
            ]],
            ['trace', Logger::LEVEL_TRACE, [
                'level' => LogLevel::DEBUG,
                'message' => 'trace',
                'context' => $context,
            ]],
            ['profile', Logger::LEVEL_PROFILE, [
                'level' => LogLevel::DEBUG,
                'message' => 'profile',
                'context' => $context,
            ]],
            ['profile_begin', Logger::LEVEL_PROFILE_BEGIN, [
                'level' => LogLevel::DEBUG,
                'message' => 'profile_begin',
                'context' => $context,
            ]],
            ['profile_end', Logger::LEVEL_PROFILE_END, [
                'level' => LogLevel::DEBUG,
                'message' => 'profile_end',
                'context' => $context,
            ]],
            // PsrLevels
            ['emergency', LogLevel::EMERGENCY, [
                'level' => LogLevel::EMERGENCY,
                'message' => 'emergency',
                'context' => $context,
            ]],
            ['alert', LogLevel::ALERT, [
                'level' => LogLevel::ALERT,
                'message' => 'alert',
                'context' => $context,
            ]],
            ['critical', LogLevel::CRITICAL, [
                'level' => LogLevel::CRITICAL,
                'message' => 'critical',
                'context' => $context,
            ]],
            ['error', LogLevel::ERROR, [
                'level' => LogLevel::ERROR,
                'message' => 'error',
                'context' => $context,
            ]],
            ['warning', LogLevel::WARNING, [
                'level' => LogLevel::WARNING,
                'message' => 'warning',
                'context' => $context,
            ]],
            ['notice', LogLevel::NOTICE, [
                'level' => LogLevel::NOTICE,
                'message' => 'notice',
                'context' => $context,
            ]],
            ['info', LogLevel::INFO, [
                'level' => LogLevel::INFO,
                'message' => 'info',
                'context' => $context,
            ]],
            ['debug', LogLevel::DEBUG, [
                'level' => LogLevel::DEBUG,
                'message' => 'debug',
                'context' => $context,
            ]],
        ];
    }

    /**
     * @dataProvider testLogDataProvider
     * @param string $message
     * @param int $level
     * @param mixed $expected
     */
    public function testLog($message, $level, $expected)
    {
        $psrLogger = new PsrArrayLogger();

        $logger = new Logger();
        new Dispatcher([
            'logger' => $logger,
            'targets' => [
                'psr' => [
                    'class' => 'samdark\log\PsrTarget',
                    'logger' => $psrLogger,
                ]
            ]
        ]);

        $logger->log($message, $level);
        $logger->flush(true);

        $logEntry = isset($psrLogger->logs[0]) ? $psrLogger->logs[0] : null;
        if (isset($logEntry['context']['memory'])) {
            $logEntry['context']['memory'] = 42;
        }

        $this->assertEquals($expected, $logEntry);
    }
}
