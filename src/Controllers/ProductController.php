<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProductService;

final class ProductController
{
    public function __construct(
        private ProductService $products
    ) {}

    public function index(): void
    {
        $start = microtime(true);

        $result = $this->products->findAll();

        $this->respond($result, $start);
    }

    public function show(int $id): void
    {
        $start = microtime(true);

        $result = $this->products->findById($id);

        if ($result['data'] === null) {
            $this->respond($result, $start, 404);
            return;
        }

        $this->respond($result, $start);
    }

    private function respond(
        array $result,
        float $start,
        int $status = 200
    ): void {
        $duration = round(
            (microtime(true) - $start) * 1000,
            3
        );

        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Cache-Source: ' . $result['source']);
        header('X-Response-Time-Ms: ' . $duration);

        echo json_encode([
            'source' => $result['source'],
            'execution_time_ms' => $duration,
            'data' => $result['data']
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}