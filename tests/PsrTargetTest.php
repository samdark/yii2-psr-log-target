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
            ['profile_begin', Logger::LEVEL_PROFILE_BEGIN, null],
            ['profile_end', Logger::LEVEL_PROFILE_END, null],
        ];
    }

    /**
     * @dataProvider testLogDataProvider
     */
    public function testLog($message, $level, $expected)
    {
        $psrLogger = new PsrArrayLogger();

        $logger = new Logger();
        $dispatcher = new Dispatcher([
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
