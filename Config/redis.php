<?php

declare(strict_types=1);

use Predis\Client;

return new Client([
    'scheme' => 'tcp',
    'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
    'port' => (int) ($_ENV['REDIS_PORT'] ?? 6379),
    'timeout' => 1.0,
    'read_write_timeout' => 1.0,
]);