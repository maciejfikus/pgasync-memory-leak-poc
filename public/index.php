<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$client = new PgAsync\Client([
    'host' => '172.29.0.1',
    'port' => 54320,
    'user' => 'devuser',
    'password' => 'devsecret',
    'database' => 'devdb'
]);

for($i = 1; $i <= 30000; $i++) {
    $client->query('SELECT 1')->subscribe(
        fn($result) => 5,
        fn($result) => 5,
        fn() => 5,
    );
}

sleep(30);

function memory() {
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

var_dump(memory());
