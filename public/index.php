<?php

declare(strict_types=1);

use PgAsync\Connection;
use React\EventLoop\Loop;

require __DIR__ . '/../vendor/autoload.php';

$connection = new Connection([
    'host'     => '172.29.0.1',
    'port'     => 54320,
    'user'     => 'devuser',
    'password' => 'devsecret',
    'database' => 'devdb'
], Loop::get());

for ($i = 1; $i <= 30000; $i++) {
    $connection->query('SELECT 1')->subscribe(
        fn($result) => var_dump($result),
        fn($ex) => 5,
        fn() => 5,
    );
}

Loop::addPeriodicTimer(10, function () use ($connection) {
    var_dump(
        $connection->getBacklogLength(),
        memory()
    );
});

function memory()
{
    $memory_size = memory_get_usage();
    $memory_size_real = memory_get_usage(true);
    $memory_size_peak_real = memory_get_peak_usage(true);
    $memory_unit = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB');

    return [
        'Used Memory : ' . round(
            $memory_size / (1024 ** ($x = floor(log($memory_size, 1024)))),
            2
        ) . ' ' . $memory_unit[$x],
        'Used Real Memory : ' . round(
            $memory_size_real / (1024 ** ($x = floor(log($memory_size_real, 1024)))),
            2
        ) . ' ' . $memory_unit[$x],
        'Used Real Peak Memory : ' . round(
            $memory_size_peak_real / (1024 ** ($x = floor(log($memory_size_peak_real, 1024)))),
            2
        ) . ' ' . $memory_unit[$x],
    ];
}
