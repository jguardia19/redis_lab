<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ProductRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function findAll(): array
    {
        $sql = "
            SELECT
                p.id,
                p.sku,
                p.nombre,
                c.nombre AS categoria,
                p.precio,
                p.stock
            FROM productos p
            INNER JOIN categorias c
                ON c.id = p.categoria_id
            WHERE p.activo = 1
            ORDER BY p.id DESC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM productos WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        $product = $stmt->fetch();

        return $product === false ? null : $product;
    }
}