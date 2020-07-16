# Yii 2 PSR Log Target

Allows you to process logs using any PSR-3 compatible logger such as [Monolog](https://github.com/Seldaek/monolog).


[![Latest Stable Version](https://poser.pugx.org/samdark/yii2-psr-log-target/v/stable.png)](https://packagist.org/packages/samdark/yii2-psr-log-target)
[![Total Downloads](https://poser.pugx.org/samdark/yii2-psr-log-target/downloads.png)](https://packagist.org/packages/samdark/yii2-psr-log-target)
[![Build Status](https://github.com/samdark/yii2-psr-log-target/workflows/build/badge.svg)](https://github.com/samdark/yii2-psr-log-target/actions?query=workflow%3Abuild)
[![Code Coverage](https://scrutinizer-ci.com/g/samdark/yii2-psr-log-target/badges/coverage.png?s=31d80f1036099e9d6a3e4d7738f6b000b3c3d10e)](https://scrutinizer-ci.com/g/samdark/yii2-psr-log-target)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/samdark/yii2-psr-log-target/badges/quality-score.png?s=b1074a1ff6d0b214d54fa5ab7abbb90fc092471d)](https://scrutinizer-ci.com/g/samdark/yii2-psr-log-target/)

## Installation

```
composer require "samdark/yii2-psr-log-target"
```

## Usage

In order to use `PsrTarget` you should configure your `log` application component like the following:  

```php
// $psrLogger should be an instance of PSR-3 compatible logger.
// As an example, we'll use Monolog to send log to Slack.
$psrLogger = new \Monolog\Logger('my_logger');
$psrLogger->pushHandler(new \Monolog\Handler\SlackHandler('slack_token', 'logs', null, true, null, \Monolog\Logger::DEBUG));

return [
    // ...
    'bootstrap' => ['log'],    
    // ...    
    'components' => [
        // ...        
        'log' => [
            'targets' => [
                [
                    'class' => 'samdark\log\PsrTarget',
                    'logger' => $psrLogger,
                    
                    // It is optional parameter. The message levels that this target is interested in.
                    // The parameter can be an array.
                    'levels' => ['info', yii\log\Logger::LEVEL_WARNING, Psr\Log\LogLevel::CRITICAL],
                    // It is optional parameter. Default value is false. If you use Yii log buffering, you see buffer write time, and not real timestamp.
                    // If you want write real time to logs, you can set addTimestampToContext as true and use timestamp from log event context.
                    'addTimestampToContext' => true,
                ],
                // ...
            ],
        ],
    ],
];
```

Standard usage:

```php
Yii::info('Info message');
Yii::error('Error message');
```

Usage with PSR logger levels:

```php
Yii::getLogger()->log('Critical message', Psr\Log\LogLevel::CRITICAL);
Yii::getLogger()->log('Alert message', Psr\Log\LogLevel::ALERT);
```

Usage with original timestamp from context in the log:

```php
// $psrLogger should be an instance of PSR-3 compatible logger.
// As an example, we'll use Monolog to send log to Slack.
$psrLogger = new \Monolog\Logger('my_logger');

$psrLogger->pushProcessor(function($record) {
    if (isset($record['context']['timestamp'])) {
        $dateTime = DateTime::createFromFormat('U.u', $record['context']['timestamp']);
        $timeZone = $record['datetime']->getTimezone();
        $dateTime->setTimezone($timeZone);
        $record['datetime'] = $dateTime;

        unset($record['context']['timestamp']);
    }

    return $record;
});
```

You can use PsrMessage instead of regular string messages to add custom context.

Standard usage:

```php
Yii::error(new \samdark\log\PsrMessage("Critical message", [
    'custom' => 'context',
    'key' => 'value',
]));
```

Usage with PSR logger Levels:

```php
Yii::getLogger()->log(new \samdark\log\PsrMessage("Critical message", [
    'important' => 'context'
]), Psr\Log\LogLevel::CRITICAL);
```

Usage with PSR-3 log message processing:
```php
$psrLogger = new \Monolog\Logger('my_logger');
$psrLogger->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());
```

```php
Yii::debug(new \samdark\log\PsrMessage("Greetings from {fruit}", [
    'fruit' => 'banana'
]));
```

## Running tests

In order to run tests perform the following commands:

```
composer install
./vendor/bin/phpunit
```
