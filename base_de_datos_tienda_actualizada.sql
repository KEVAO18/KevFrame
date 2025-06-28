CREATE DATABASE IF NOT EXISTS db_tienda;

-- tabla para los estados de los productos
CREATE TABLE IF NOT EXISTS db_tienda.estados_producto (

    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- tabla para los productos
CREATE TABLE IF NOT EXISTS db_tienda.productos (

    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(30) NOT NULL,
    descripcion varchar(254),
    unidades int NOT NULL,
    precio FLOAT NOT NULL,
    estado_id INT NOT NULL DEFAULT 1,

    CONSTRAINT productos_estado_fk
    FOREIGN KEY (estado_id)
    REFERENCES estados_producto(id)
    ON DELETE RESTRICT
);

-- tabla para los tipos de productos
CREATE TABLE IF NOT EXISTS db_tienda.categorias (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(99) NOT NULL
);

-- Tabla de relación para productos y categoría
CREATE TABLE IF NOT EXISTS db_tienda.producto_categoria (

    producto_id INT NOT NULL,
    categoria_id INT NOT NULL,

    PRIMARY KEY (producto_id, categoria_id),
    FOREIGN KEY (producto_id) 
    REFERENCES productos(id) 
    ON DELETE CASCADE,

    FOREIGN KEY (categoria_id) 
    REFERENCES categorias(id) 
    ON DELETE CASCADE
);

-- tabla para los atributos
CREATE TABLE IF NOT EXISTS db_tienda.atributos (

    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- tabla para los valores de los atributos
CREATE TABLE IF NOT EXISTS db_tienda.valores_atributo (

    id INT AUTO_INCREMENT PRIMARY KEY,
    atributo_id INT NOT NULL,
    valor VARCHAR(100) NOT NULL,

    FOREIGN KEY (atributo_id) 
    REFERENCES atributos(id) 
    ON DELETE CASCADE
);

-- tabla para la relación entre productos y valores de atributos
CREATE TABLE IF NOT EXISTS db_tienda.producto_atributo_valor (

    producto_id INT NOT NULL,
    valor_atributo_id INT NOT NULL,

    PRIMARY KEY (producto_id, valor_atributo_id),

    FOREIGN KEY (producto_id) 
    REFERENCES productos(id) 
    ON DELETE CASCADE,

    FOREIGN KEY (valor_atributo_id) 
    REFERENCES valores_atributo(id) 
    ON DELETE CASCADE
);

-- tabla para los usuarios
CREATE TABLE IF NOT EXISTS db_tienda.usuarios (

    dni int NOT NULL PRIMARY KEY,
    fullname varchar(255) NOT NULL,
    userName varchar(40) NOT NULL,
    email varchar(50) NOT NULL,
    pass varchar(100) NOT NULL,
    salt varchar(100) NOT NULL,
    usuario_activo BOOLEAN DEFAULT TRUE
);

-- tabla para los tipos de credenciales
CREATE TABLE IF NOT EXISTS db_tienda.tipoCredencial (

    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descripcion varchar(255) NOT NULL
);

-- tabla para las credenciales
CREATE TABLE IF NOT EXISTS db_tienda.credenciales (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario int NOT NULL,
    tipo int NOT NULL,
    
    CONSTRAINT credenciales_tipo_fk
    FOREIGN KEY (tipo)
    REFERENCES tipoCredencial (id)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
    
    CONSTRAINT credenciales_usuario_fk
    FOREIGN KEY (usuario)
    REFERENCES usuarios (dni)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- tabla para las facturas
CREATE TABLE IF NOT EXISTS db_tienda.factura (

    id varchar(10) NOT NULL PRIMARY KEY,
    usuario int NOT NULL,
    fecha datetime NOT NULL,
    total float NOT NULL,

    CONSTRAINT factura_usuario_fk 
    FOREIGN KEY (usuario) 
    REFERENCES usuarios (dni)
	ON DELETE CASCADE
);

-- tabla para las ventas
CREATE TABLE IF NOT EXISTS db_tienda.ventas (

    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    producto int NOT NULL,
    cantidad int NOT NULL,
    factura varchar(10) NOT NULL,
    fecha DATETIME NOT NULL,

    CONSTRAINT ventas_producto_fk 
    FOREIGN KEY (producto) 
    REFERENCES productos (id)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,

    CONSTRAINT ventas_factura_fk 
    FOREIGN KEY (factura) 
    REFERENCES factura (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- tabla para los carritos
CREATE TABLE IF NOT EXISTS db_tienda.carrito (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT carrito_usuario_fk 
    FOREIGN KEY (usuario) 
    REFERENCES usuarios(dni)
    ON DELETE CASCADE
);

-- tabla para los detalles de los carritos
CREATE TABLE IF NOT EXISTS db_tienda.carrito_detalle (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    carrito INT NOT NULL,
    producto INT NOT NULL,
    cantidad INT NOT NULL,

    CONSTRAINT carrito_detalle_carrito_fk 
    FOREIGN KEY (carrito) 
    REFERENCES carrito(id)
    ON DELETE CASCADE,

    CONSTRAINT carrito_detalle_producto_fk
    FOREIGN KEY (producto) 
    REFERENCES productos(id)
	ON DELETE CASCADE
);

-- tabla para los pedidos
CREATE TABLE IF NOT EXISTS db_tienda.pedidos (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'procesando', 'enviado', 'completado', 'cancelado') NOT NULL DEFAULT 'pendiente',
    total FLOAT NOT NULL,

    CONSTRAINT pedidos_usuario_fk 
    FOREIGN KEY (usuario) 
    REFERENCES usuarios(dni)
    ON DELETE CASCADE
);

-- tabla para los detalles de los pedidos
CREATE TABLE IF NOT EXISTS db_tienda.pedido_detalle (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pedido INT NOT NULL,
    producto INT NOT NULL,
    cantidad INT NOT NULL,

    CONSTRAINT pedido_detalle_pedido_fk 
    FOREIGN KEY (pedido) 
    REFERENCES pedidos(id)
    ON DELETE CASCADE,

    CONSTRAINT pedido_detalle_producto_fk 
    FOREIGN KEY (producto) 
    REFERENCES productos(id)
    ON DELETE CASCADE
);

-- tabla para las devoluciones
CREATE TABLE IF NOT EXISTS db_tienda.devoluciones (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    producto INT NOT NULL,
    factura varchar(10) NOT NULL,
    motivo text NOT NULL,
    reembolso FLOAT NOT NULL,
    estado ENUM('pendiente', 'aceptada', 'rechazada') NOT NULL DEFAULT 'pendiente',
    fecha_ingreso DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_final DATETIME,
    
    CONSTRAINT inventario_historial_producto_fk
    FOREIGN KEY (producto) 
    REFERENCES productos(id)
	ON DELETE CASCADE
);

-- tabla para los cupones
CREATE TABLE IF NOT EXISTS db_tienda.cupones (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    descuento FLOAT NOT NULL,
    tipo ENUM('porcentaje', 'fijo') NOT NULL, -- Porcentaje o descuento fijo
    valido_desde DATE NOT NULL,
    valido_hasta DATE NOT NULL,
    limite_uso INT NOT NULL DEFAULT 1 -- Veces que puede usarse
);

-- tabla para los cupones usados
CREATE TABLE IF NOT EXISTS db_tienda.cupones_uso (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cupon INT NOT NULL,
    usuario INT NOT NULL,
    fecha_uso DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT cupones_uso_cupon_fk
    FOREIGN KEY (cupon) 
    REFERENCES cupones(id)
    ON DELETE CASCADE,

    CONSTRAINT cupones_uso_usuario_fk
    FOREIGN KEY (usuario) 
    REFERENCES usuarios(dni)
	ON DELETE CASCADE
);

-- tabla para los metodos de pago
CREATE TABLE IF NOT EXISTS db_tienda.metodos_pago (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS db_tienda.estados_pago (
    
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- tabla para los pagos
CREATE TABLE IF NOT EXISTS db_tienda.pagos (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pedido INT NOT NULL,
    metodo_pago INT NOT NULL,
    monto FLOAT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado INT NOT NULL DEFAULT 1,
    
    CONSTRAINT pagos_pedido_fk
    FOREIGN KEY (pedido) 
    REFERENCES pedidos(id)
    ON DELETE CASCADE,
    
    CONSTRAINT pagos_metodo_pago_fk
    FOREIGN KEY (metodo_pago) 
    REFERENCES metodos_pago(id)
	ON DELETE CASCADE,

    CONSTRAINT pagos_estado_fk
    FOREIGN KEY (estado)
    REFERENCES estados_pago(id)
    ON DELETE CASCADE
);

-- tabla para el stock
CREATE TABLE IF NOT EXISTS db_tienda.stock (

    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    producto int,
    agotado tinyint,

    CONSTRAINT stock_producto_fk 
    FOREIGN KEY (producto) 
    REFERENCES productos (id)
    ON DELETE CASCADE    
);

-- tabla para almacenar las tirillas de los pagos
CREATE TABLE IF NOT EXISTS db_tienda.tirillas (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pago_id INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_generacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT tirillas_pago_fk FOREIGN KEY (pago_id)
    REFERENCES pagos(id)
    ON DELETE CASCADE
);

-- tabla para almacenar las direcciones de envío
CREATE TABLE IF NOT EXISTS db_tienda.direcciones_envio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario INT NOT NULL,
    direccion TEXT NOT NULL,
    ciudad VARCHAR(100),
    departamento VARCHAR(100),
    pais VARCHAR(100),
    principal BOOLEAN DEFAULT FALSE,

    CONSTRAINT direcciones_envio_usuario_fk FOREIGN KEY (usuario)
    REFERENCES usuarios(dni)
    ON DELETE CASCADE
);

-- tabla para auditoría de acciones de usuario
CREATE TABLE IF NOT EXISTS db_tienda.log_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario INT,
    accion VARCHAR(255),
    detalle TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario) REFERENCES usuarios(dni)
);

-- tabla para mantener trazabilidad si cambias el precio
CREATE TABLE IF NOT EXISTS db_tienda.historial_precios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    precio FLOAT NOT NULL,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATETIME,

    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

CREATE INDEX IF NOT EXISTS index_usuarios ON db_tienda.usuarios (userName, email);
CREATE INDEX IF NOT EXISTS index_factura ON db_tienda.factura (usuario, fecha);
CREATE INDEX IF NOT EXISTS index_ventas ON db_tienda.ventas (factura, producto, fecha);
CREATE INDEX IF NOT EXISTS index_carrito ON db_tienda.carrito (usuario);
CREATE INDEX IF NOT EXISTS index_carrito_detalle ON db_tienda.carrito_detalle (producto, carrito);
CREATE INDEX IF NOT EXISTS index_pedidos ON db_tienda.pedidos (usuario);
CREATE INDEX IF NOT EXISTS index_pedido_detalle ON db_tienda.pedido_detalle (pedido);
CREATE INDEX IF NOT EXISTS index_devoluciones ON db_tienda.devoluciones (producto, factura, estado);
CREATE INDEX IF NOT EXISTS index_cupones_codigo ON db_tienda.cupones (codigo);
CREATE INDEX IF NOT EXISTS index_cupones_tipo ON db_tienda.cupones (tipo);
CREATE INDEX IF NOT EXISTS index_cupones_uso_usuario ON db_tienda.cupones_uso (usuario);
CREATE INDEX IF NOT EXISTS index__cupones_uso_cupon ON db_tienda.cupones_uso (cupon);
CREATE INDEX IF NOT EXISTS index_metodos_pago ON db_tienda.metodos_pago (nombre);
CREATE INDEX IF NOT EXISTS index_pagos ON db_tienda.pagos (pedido, metodo_pago);


-- vistas
-- vista para obtener las categorias con la cantidad de productos que tienen
create view productos_por_categoria as
select 
    c.id, 
    c.descripcion as "categoria", 
    COUNT(pc.categoria_id) as "cantidad" 
from 
    categorias as c 
inner join 
    producto_categoria as pc 
on 
    c.id = pc.categoria_id 
group by 
    c.descripcion;


-- Procedimientos almacenados
-- Procedimiento para obtener solo un numero limitado de productos para la paginacion
CREATE PROCEDURE IF NOT EXISTS db_tienda.`paginacion_productos`(
    IN `f` INT, 
    IN `l` INT
)
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
SELECT 
    * 
FROM 
    productos 
WHERE 
    id BETWEEN f AND l;

-- Procedimiento para obtener los productos mas vendidos
CREATE PROCEDURE IF NOT EXISTS db_tienda.`productos_mas_vendidos`(IN `num` INT(1))
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
SELECT 
    * 
FROM 
    productos 
WHERE id IN (
    SELECT
        producto
    FROM ( 
        SELECT 
            producto, 
            SUM(
                cantidad
            ) AS cantidad 
        FROM 
            ventas 
        GROUP BY 
            producto 
        ORDER BY 
            cantidad DESC 
        LIMIT 
            num 
    ) AS top_productos_subquery 
);

-- Procedimiento para obtener los productos mas vendidos con su cantidad total vendida
CREATE PROCEDURE IF NOT EXISTS db_tienda.`productos_mas_vendidos_detallado`(IN `num` INT)
    READS SQL DATA
SELECT
    p.*,
    tp.cantidad_total_vendida
FROM
    productos p
JOIN (
    SELECT
        producto,
        SUM(cantidad) AS cantidad_total_vendida
    FROM
        ventas
    GROUP BY
        producto
    ORDER BY
        cantidad_total_vendida DESC
    LIMIT num
) AS tp ON p.id = tp.producto;

-- Procedimiento para obtener los atributos de un producto en especifico
CREATE PROCEDURE IF NOT EXISTS db_tienda.`atributos_producto`(IN `prod` INT)
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
SELECT
    a.nombre, 
    va.valor 
FROM 
	`producto_atributo_valor` as pav 
inner join 
	valores_atributo as va 
on 
	pav.valor_atributo_id = va.id 
inner join 
	productos as p 
on 
	p.id = pav.producto_id 
inner join 
	atributos as a 
on 
	a.id = va.atributo_id 
WHERE 
	pav.producto_id = prod;

-- Procedimiento para obtener los productos de una categoria en especifico
CREATE PROCEDURE IF NOT EXISTS db_tienda.`filtro_categoria`(IN num INT) 
    NOT DETERMINISTIC 
    CONTAINS SQL 
    SQL SECURITY DEFINER 
SELECT 
    p.* 
FROM 
    productos AS p 
INNER JOIN 
    producto_categoria AS pc 
ON 
    pc.producto_id = p.id 
INNER JOIN 
    categorias AS c 
ON 
    c.id = pc.categoria_id 
WHERE 
    c.id = num;

-- Procedimiento para obtener los productos mas recientes
CREATE PROCEDURE IF NOT EXISTS db_tienda.`nuevos`(IN `lim` INT) 
    NOT DETERMINISTIC 
    CONTAINS SQL 
    SQL SECURITY DEFINER 
SELECT 
    * 
FROM 
    `productos` 
order by
    id DESC 
limit 
    lim;

-- procedimiento para logear un usuario por email
CREATE PROCEDURE IF NOT EXISTS db_tienda.`login`(IN `mail` VARCHAR(50))
    NOT DETERMINISTIC 
    CONTAINS SQL 
    SQL SECURITY DEFINER 
SELECT 
    * 
FROM 
    usuarios AS u 
WHERE 
    u.email = mail 
limit 
    1;