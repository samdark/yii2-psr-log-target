<?php
namespace samdark\log\tests;

use Psr\Log\LogLevel;
use samdark\log\PsrTarget;
use yii\base\InvalidConfigException;
use yii\log\Dispatcher;
use yii\log\Logger;

class PsrTargetLevelsTest extends \PHPUnit_Framework_TestCase
{
    public function testYiiLevelsDataProvider()
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
            ['emergency', LogLevel::EMERGENCY, null],
            ['alert', LogLevel::ALERT, null],
            ['critical', LogLevel::CRITICAL, null],
            ['error', LogLevel::ERROR, null],
            ['warning', LogLevel::WARNING, null],
            ['notice', LogLevel::NOTICE, null],
            ['info', LogLevel::INFO, null],
            ['debug', LogLevel::DEBUG, null],
        ];
    }

    /**
     * @dataProvider testYiiLevelsDataProvider
     * @param string $message
     * @param int $level
     * @param mixed $expected
     */
    public function testYiiLevelsFilter($message, $level, $expected)
    {
        $psrLogger = new PsrArrayLogger();
        $logger = new Logger();
        new Dispatcher([
            'logger' => $logger,
            'targets' => [
                'psr' => [
                    'class' => 'samdark\log\PsrTarget',
                    'logger' => $psrLogger,
                    'levels' => [
                        Logger::LEVEL_ERROR, Logger::LEVEL_WARNING, Logger::LEVEL_INFO,  Logger::LEVEL_INFO,
                        Logger::LEVEL_TRACE, Logger::LEVEL_PROFILE, Logger::LEVEL_PROFILE_BEGIN, Logger::LEVEL_PROFILE_END
                    ],
                    'logVars' => [],
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

    public function testPsrLevelsDataProvider()
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

            ['trace', Logger::LEVEL_TRACE, null],
            ['profile', Logger::LEVEL_PROFILE, null],
            ['profile_begin', Logger::LEVEL_PROFILE_BEGIN, null],
            ['profile_end', Logger::LEVEL_PROFILE_END, null],
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
     * @dataProvider testPsrLevelsDataProvider
     * @param string $message
     * @param int $level
     * @param mixed $expected
     */
    public function testPsrLevelsFilter($message, $level, $expected)
    {
        $psrLogger = new PsrArrayLogger();
        $logger = new Logger();
        new Dispatcher([
            'logger' => $logger,
            'targets' => [
                'psr' => [
                    'class' => 'samdark\log\PsrTarget',
                    'logger' => $psrLogger,
                    'levels' => [
                        LogLevel::INFO, LogLevel::ERROR, LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY,
                        LogLevel::NOTICE, LogLevel::DEBUG, LogLevel::WARNING,
                    ],
                    'logVars' => [],
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

    public function testMixedLevelsDataProvider()
    {
        $context = [
            'category' => 'application',
            'memory' => 42,
            'trace' => [],
        ];

        return [
            ['error', Logger::LEVEL_ERROR, null],
            ['warning', Logger::LEVEL_WARNING, [
                'level' => LogLevel::WARNING,
                'message' => 'warning',
                'context' => $context,
            ]],
            ['info', Logger::LEVEL_INFO, null],
            ['trace', Logger::LEVEL_TRACE, null],
            ['profile', Logger::LEVEL_PROFILE, null],
            ['profile_begin', Logger::LEVEL_PROFILE_BEGIN, null],
            ['profile_end', Logger::LEVEL_PROFILE_END, null],
            // PsrLevels
            ['emergency', LogLevel::EMERGENCY, [
                'level' => LogLevel::EMERGENCY,
                'message' => 'emergency',
                'context' => $context,
            ]],
            ['alert', LogLevel::ALERT, null],
            ['critical', LogLevel::CRITICAL, null],
            ['error', LogLevel::ERROR, null],
            ['warning', LogLevel::WARNING, null],
            ['notice', LogLevel::NOTICE, null],
            ['info', LogLevel::INFO, null],
            ['debug', LogLevel::DEBUG, [
                'level' => LogLevel::DEBUG,
                'message' => 'debug',
                'context' => $context,
            ]],
        ];
    }

    /**
     * @dataProvider testMixedLevelsDataProvider
     * @param string $message
     * @param int $level
     * @param mixed $expected
     */
    public function testMixedLevelsFilter($message, $level, $expected)
    {
        $psrLogger = new PsrArrayLogger();
        $logger = new Logger();
        new Dispatcher([
            'logger' => $logger,
            'targets' => [
                'psr' => [
                    'class' => 'samdark\log\PsrTarget',
                    'logger' => $psrLogger,
                    'levels' => [
                        'debug', LogLevel::EMERGENCY, Logger::LEVEL_WARNING,
                    ],
                    'logVars' => [],
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

    public function testIncorrectLevelsTypeFilter()
    {
        $this->setExpectedException('yii\base\InvalidConfigException');
        new PsrTarget(['levels' => 'string']);
    }

    public function testIncorrectLevelsFilter()
    {
        $this->setExpectedException('yii\base\InvalidConfigException');
        new PsrTarget(['levels' => ['not existing level']]);
    }

    public function testContextMessageNotFiltered()
    {
        $psrLogger = new PsrArrayLogger();
        $logger = new Logger();
        new Dispatcher([
            'logger' => $logger,
            'targets' => [
                'psr' => [
                    'class' => 'samdark\log\PsrTarget',
                    'logger' => $psrLogger,
                    'levels' => [
                        'debug', LogLevel::EMERGENCY, Logger::LEVEL_WARNING,
                    ],
                ]
            ]
        ]);

        $logger->log('test message', Logger::LEVEL_WARNING);
        $logger->flush(true);

        $contextMessage = isset($psrLogger->logs[1]) ? $psrLogger->logs[1] : null;

        $this->assertNotNull($contextMessage);
        $this->assertEquals(LogLevel::INFO, $contextMessage['level']);
    }
}
