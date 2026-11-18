<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ProductRepository;

final class ProductController
{
    public function __construct(
        private ProductRepository $products
    ) {}

    public function index(): void
    {
        $data = $this->products->findAll();

        $this->json($data);
    }

    public function show(int $id): void
    {
        $product = $this->products->findById($id);

        if ($product === null) {
            $this->json(
                ['error' => 'Producto no encontrado'],
                404
            );
            return;
        }

        $this->json($product);
    }

    private function json(
        array $data,
        int $status = 200
    ): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE |
            JSON_THROW_ON_ERROR
        );
    }
}