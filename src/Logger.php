<?php
namespace Src;

class Logger
{
    public static function write(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        file_put_contents(__DIR__ . '/../log.txt', "[$date] $message" . PHP_EOL, FILE_APPEND);
    }
}
