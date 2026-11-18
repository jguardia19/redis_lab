<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use Predis\Client;
use Throwable;

final class CacheService
{
    public function __construct(
        private Client $redis,
        private string $prefix = 'redis_lab:'
    ) {}

    public function get(string $key): ?string
    {
        try {
            return $this->redis->get(
                $this->prefix . $key
            );
        } catch (Throwable $e) {
            error_log('Redis GET: ' . $e->getMessage());
            return null;
        }
    }

    public function set(
        string $key,
        string $value,
        int $ttl = 300
    ): bool {
        try {
            $result = $this->redis->setex(
                $this->prefix . $key,
                $ttl,
                $value
            );

            return (string) $result === 'OK';
        } catch (Throwable $e) {
            error_log('Redis SET: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(string $key): bool
    {
        try {
            $this->redis->del([
                $this->prefix . $key
            ]);

            return true;
        } catch (Throwable $e) {
            error_log('Redis DEL: ' . $e->getMessage());
            return false;
        }
    }
}