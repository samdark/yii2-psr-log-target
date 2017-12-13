# Yii 2 PSR Log Target

Allows you to process logs using any PSR-3 compatible logger such as [Monolog](https://github.com/Seldaek/monolog).

<a href="https://travis-ci.org/samdark/yii2-psr-log-target">
    <img src="https://travis-ci.org/samdark/yii2-psr-log-target.svg" />
</a>

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

## Running tests

In order to run tests perform the following commands:

```
composer install
./vendor/bin/phpunit
```
