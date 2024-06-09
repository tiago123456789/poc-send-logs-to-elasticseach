<?php

require __DIR__ . "/vendor/autoload.php"; // This tells PHP where to find the autoload file so that PHP can load the installed packages

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger; // The Logger instance
use Monolog\Handler\StreamHandler; // The StreamHandler sends log messages to a file on your disk


$logger = new Logger("logger");

class MyOwnFormatter extends JsonFormatter
{
    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     * @return mixed The formatted record
     *
     * @phpstan-param Record $record
     */
    public function format(array $record): string
    {
        return json_encode([
            "level" => $record["level_name"],
            "message" => $record["message"],
            "service" => $record["service"],
            "timestamp" => $record["datetime"]
        ]) . "\n";
    }
};

// $stream_handler = new StreamHandler("php://stdout", Logger::INFO);
$stream_handler = new StreamHandler(__DIR__ . "/logs/logs-lambda-php.log", Logger::INFO);

$stream_handler->setFormatter(new MyOwnFormatter());
$logger->pushHandler($stream_handler);
$logger->setTimezone(new \DateTimeZone('UTC'));

$logger->pushProcessor(function ($record) {
    $record['service'] = 'lambda-php';
    return $record;
});

function setInterval($f, $milliseconds)
{
    $seconds=(int)$milliseconds/1000;
    while(true)
    {
        $f();
        sleep($seconds);
    }
}

setInterval(function() use($logger) {
    $logger->info("Test monolog library at " . date('Y-m-d H:i:s'));
}, 1000);
