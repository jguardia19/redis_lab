
USE redis_lab;

-- CATEGORIAS
INSERT INTO categorias (id, nombre, descripcion) VALUES
(1, 'Lamparas', 'Lamparas UV y LED'),
(2, 'Geles', 'Geles semipermanentes'),
(3, 'Herramientas', 'Herramientas profesionales'),
(4, 'Accesorios', 'Accesorios de belleza'),
(5, 'Equipos', 'Equipos electricos');

-- PRODUCTOS
INSERT INTO productos
(id, categoria_id, sku, nombre, precio, stock)
VALUES
(1, 1, 'LAM-001', 'Lampara UV 48W', 350, 100),
(2, 1, 'LAM-002', 'Lampara SUN X5', 420, 80),
(3, 2, 'GEL-001', 'Gel Semipermanente Rojo', 65, 300),
(4, 2, 'GEL-002', 'Gel Semipermanente Nude', 65, 250),
(5, 3, 'HER-001', 'Pulidor Profesional', 850, 50),
(6, 3, 'HER-002', 'Brocha Profesional', 95, 120),
(7, 4, 'ACC-001', 'Organizador de Esmaltes', 180, 75),
(8, 4, 'ACC-002', 'Cepillo de Unas', 35, 400),
(9, 5, 'EQU-001', 'Fundidor de Cera', 550, 60),
(10, 5, 'EQU-002', 'Cepillo Alaciador', 690, 45);

-- CLIENTES
INSERT INTO clientes (id, nombre, email) VALUES
(1, 'Ana Lopez', 'ana@example.com'),
(2, 'Carlos Perez', 'carlos@example.com'),
(3, 'Maria Garcia', 'maria@example.com'),
(4, 'Luis Hernandez', 'luis@example.com'),
(5, 'Sofia Ramirez', 'sofia@example.com');

-- PEDIDOS
INSERT INTO pedidos
(id, cliente_id, producto_id, cantidad,
 precio_unitario, total, estado)
VALUES
(1, 1, 1, 2, 350, 700, 'pagado'),
(2, 2, 3, 5, 65, 325, 'entregado'),
(3, 3, 5, 1, 850, 850, 'pendiente'),
(4, 4, 2, 1, 420, 420, 'pagado'),
(5, 5, 7, 3, 180, 540, 'entregado'),
(6, 1, 9, 1, 550, 550, 'pendiente'),
(7, 2, 4, 10, 65, 650, 'pagado'),
(8, 3, 6, 2, 95, 190, 'cancelado'),
(9, 4, 10, 1, 690, 690, 'entregado'),
(10, 5, 8, 4, 35, 140, 'pagado');
