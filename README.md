# Redis Lab

Laboratorio en PHP que consulta productos guardados en MySQL y los cachea en Redis. La primera petición lee la base de datos y guarda el resultado; las siguientes, mientras el dato siga vigente, responden desde Redis.

La API indica de dónde salió cada respuesta (`mysql` o `redis`) y cuántos milisegundos tardó.

## Requisitos

- PHP 8.1 o superior, con las extensiones `pdo_mysql` y `json`
- [Composer](https://getcomposer.org/)
- MySQL 8 (o MariaDB compatible con InnoDB)
- Redis en ejecución (por defecto en `127.0.0.1:6379`)

En Laragon, MySQL y Redis se pueden iniciar desde el menú del programa.

## Instalación

Desde la raíz del proyecto:

```bash
composer install
```

## Archivo `.env`

Crea un archivo `.env` en la raíz del proyecto (junto a `composer.json`). Ese archivo no se versiona: cada máquina tiene el suyo.

```env
APP_ENV=local
APP_DEBUG=true

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=redis_lab
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PREFIX=redis_lab:
```

| Variable | Uso |
| --- | --- |
| `DB_HOST` | Host de MySQL |
| `DB_PORT` | Puerto de MySQL |
| `DB_DATABASE` | Nombre de la base. Debe coincidir con `redis_lab` |
| `DB_USERNAME` | Usuario de MySQL |
| `DB_PASSWORD` | Contraseña de MySQL. En Laragon local suele ir vacía |
| `REDIS_HOST` | Host de Redis |
| `REDIS_PORT` | Puerto de Redis |
| `REDIS_PREFIX` | Prefijo de las claves (`redis_lab:productos:list`, `redis_lab:producto:1`, …) |

Si omites una variable, la aplicación usa esos mismos valores por defecto.

## Base de datos

Los scripts están en `Database/`.

`Database/schema.sql` crea la base `redis_lab` y las tablas:

- `categorias`
- `productos`
- `clientes`
- `pedidos`

`Database/seed.sql` inserta datos de ejemplo: 5 categorías, 10 productos, 5 clientes y 10 pedidos.

Desde la raíz del proyecto, con el cliente de MySQL:

```bash
mysql -u root -p < Database/schema.sql
mysql -u root -p < Database/seed.sql
```

Si el usuario `root` de Laragon no tiene contraseña, omite `-p`.

También puedes abrir cada archivo en HeidiSQL, MySQL Workbench o la consola de Laragon y ejecutarlo en ese orden: primero `schema.sql`, después `seed.sql`.

## Arranque

```bash
php -S 127.0.0.1:8000 router.php
```

El servidor queda en `http://127.0.0.1:8000`.

## Endpoints

Solo aceptan `GET`.

| Ruta | Respuesta |
| --- | --- |
| `GET /api/productos` | Productos activos, con el nombre de su categoría |
| `GET /api/productos/{id}` | Un producto por id. `404` si no existe |

Ejemplo:

```bash
curl http://127.0.0.1:8000/api/productos
curl http://127.0.0.1:8000/api/productos/1
```

El cuerpo incluye `source` (`mysql` o `redis`), `execution_time_ms` y `data`. Las mismas señales van en las cabeceras `X-Cache-Source` y `X-Response-Time-Ms`.

## Cómo entra Redis

`ProductService` busca primero la clave en Redis. Si no está, o el JSON guardado no es válido, consulta MySQL y guarda el resultado durante 300 segundos.

| Consulta | Clave |
| --- | --- |
| Listado | `redis_lab:productos:list` |
| Producto por id | `redis_lab:producto:{id}` |

La primera llamada a un recurso responde con `"source": "mysql"`. La siguiente, dentro de esos 5 minutos, responde con `"source": "redis"`. Si Redis no está disponible, la petición sigue y lee MySQL; el fallo queda en el log de PHP.
