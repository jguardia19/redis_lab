<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Infrastructure\Cache\CacheService;
use JsonException;

final class ProductService
{
    public function __construct(
        private ProductRepository $products,
        private CacheService $cache
    ) {}

    public function findAll(): array
    {
        $key = 'productos:list';

        $cached = $this->cache->get($key);

        if ($cached !== null) {
            try {
                $data = json_decode(
                    $cached,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

                if (is_array($data)) {
                    return [
                        'source' => 'redis',
                        'data' => $data
                    ];
                }
            } catch (JsonException $e) {
                error_log('Cache JSON: ' . $e->getMessage());
            }
        }

        $data = $this->products->findAll();

        $this->cache->set(
            $key,
            json_encode($data, JSON_THROW_ON_ERROR),
            300
        );

        return [
            'source' => 'mysql',
            'data' => $data
        ];
    }

    public function findById(int $id): array
    {
        $key = "producto:$id";

        $cached = $this->cache->get($key);

        if ($cached !== null) {
            try {
                $data = json_decode(
                    $cached,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

                if (is_array($data)) {
                    return [
                        'source' => 'redis',
                        'data' => $data
                    ];
                }
            } catch (JsonException $e) {
                error_log('Cache JSON: ' . $e->getMessage());
            }
        }

        $data = $this->products->findById($id);

        if ($data !== null) {
            $this->cache->set(
                $key,
                json_encode($data, JSON_THROW_ON_ERROR),
                300
            );
        }

        return [
            'source' => 'mysql',
            'data' => $data
        ];
    }
}