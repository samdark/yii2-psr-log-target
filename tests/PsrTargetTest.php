<?php
declare(strict_types=1);

namespace samdark\log\tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use yii\log\Dispatcher;
use yii\log\Logger;
use samdark\log\PsrTarget;

class PsrTargetTest extends TestCase
{
    public static function logDataProvider(): array
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
     * @dataProvider logDataProvider
     */
    public function testLog(string $message, int|string $level, mixed $expected): void
    {
        $psrLogger = new PsrArrayLogger();

        $logger = new Logger();
        new Dispatcher([
            'logger' => $logger,
            'targets' => [
                'psr' => [
                    'class' => PsrTarget::class,
                    'logger' => $psrLogger,
                ]
            ]
        ]);

        $logger->log($message, $level);
        $logger->flush(true);

        $logEntry = $psrLogger->logs[0] ?? null;
        if (isset($logEntry['context']['memory'])) {
            $logEntry['context']['memory'] = 42;
        }

        $this->assertEquals($expected, $logEntry);
    }
}
