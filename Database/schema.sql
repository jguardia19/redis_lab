
CREATE DATABASE IF NOT EXISTS redis_lab
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE redis_lab;

-- ============================================
-- 1. CATEGORIAS
-- ============================================

CREATE TABLE categorias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_categorias_nombre (nombre),
    INDEX idx_categorias_activo (activo)
) ENGINE=InnoDB;


-- ============================================
-- 2. PRODUCTOS
-- ============================================

CREATE TABLE productos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id BIGINT UNSIGNED NOT NULL,
    sku VARCHAR(50) NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    precio DECIMAL(12,2) NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_productos_sku (sku),
    INDEX idx_productos_categoria (categoria_id),
    INDEX idx_productos_activo (activo),
    INDEX idx_productos_nombre (nombre),

    CONSTRAINT fk_productos_categoria
        FOREIGN KEY (categoria_id)
        REFERENCES categorias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_productos_precio
        CHECK (precio >= 0)
) ENGINE=InnoDB;


-- ============================================
-- 3. CLIENTES
-- ============================================

CREATE TABLE clientes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_clientes_email (email)
) ENGINE=InnoDB;


-- ============================================
-- 4. PEDIDOS
-- ============================================

CREATE TABLE pedidos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id BIGINT UNSIGNED NOT NULL,
    producto_id BIGINT UNSIGNED NOT NULL,
    cantidad INT UNSIGNED NOT NULL,
    precio_unitario DECIMAL(12,2) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    estado ENUM(
        'pendiente',
        'pagado',
        'cancelado',
        'entregado'
    ) NOT NULL DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_pedidos_cliente (cliente_id),
    INDEX idx_pedidos_producto (producto_id),
    INDEX idx_pedidos_estado (estado),
    INDEX idx_pedidos_fecha (created_at),

    CONSTRAINT fk_pedidos_cliente
        FOREIGN KEY (cliente_id)
        REFERENCES clientes(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_pedidos_producto
        FOREIGN KEY (producto_id)
        REFERENCES productos(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_pedidos_cantidad
        CHECK (cantidad > 0),

    CONSTRAINT chk_pedidos_precio
        CHECK (precio_unitario >= 0),

    CONSTRAINT chk_pedidos_total
        CHECK (total >= 0)
) ENGINE=InnoDB;
