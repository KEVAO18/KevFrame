CREATE DATABASE IF NOT EXISTS aromas2;

-- tabla para los estados de los productos
CREATE TABLE IF NOT EXISTS aromas2.estados_producto (

    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- tabla para los productos
CREATE TABLE IF NOT EXISTS aromas2.productos (

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
CREATE TABLE IF NOT EXISTS aromas2.categorias (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(99) NOT NULL
);

-- Tabla de relación para productos y categoría
CREATE TABLE IF NOT EXISTS aromas2.producto_categoria (

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
CREATE TABLE IF NOT EXISTS aromas2.atributos (

    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- tabla para los valores de los atributos
CREATE TABLE IF NOT EXISTS aromas2.valores_atributo (

    id INT AUTO_INCREMENT PRIMARY KEY,
    atributo_id INT NOT NULL,
    valor VARCHAR(100) NOT NULL,

    FOREIGN KEY (atributo_id) 
    REFERENCES atributos(id) 
    ON DELETE CASCADE
);

-- tabla para la relación entre productos y valores de atributos
CREATE TABLE IF NOT EXISTS aromas2.producto_atributo_valor (

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
CREATE TABLE IF NOT EXISTS aromas2.usuarios (

    dni int NOT NULL PRIMARY KEY,
    fullname varchar(255) NOT NULL,
    userName varchar(40) NOT NULL,
    email varchar(50) NOT NULL,
    pass varchar(100) NOT NULL,
    salt varchar(100) NOT NULL,
    usuario_activo BOOLEAN DEFAULT TRUE
);

-- tabla para los tipos de credenciales
CREATE TABLE IF NOT EXISTS aromas2.tipoCredencial (

    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descripcion varchar(255) NOT NULL
);

-- tabla para las credenciales
CREATE TABLE IF NOT EXISTS aromas2.credenciales (

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
CREATE TABLE IF NOT EXISTS aromas2.factura (

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
CREATE TABLE IF NOT EXISTS aromas2.ventas (

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
CREATE TABLE IF NOT EXISTS aromas2.carrito (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT carrito_usuario_fk 
    FOREIGN KEY (usuario) 
    REFERENCES usuarios(dni)
    ON DELETE CASCADE
);

-- tabla para los detalles de los carritos
CREATE TABLE IF NOT EXISTS aromas2.carrito_detalle (

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
CREATE TABLE IF NOT EXISTS aromas2.pedidos (

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
CREATE TABLE IF NOT EXISTS aromas2.pedido_detalle (

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
CREATE TABLE IF NOT EXISTS aromas2.devoluciones (

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
CREATE TABLE IF NOT EXISTS aromas2.cupones (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    descuento FLOAT NOT NULL,
    tipo ENUM('porcentaje', 'fijo') NOT NULL, -- Porcentaje o descuento fijo
    valido_desde DATE NOT NULL,
    valido_hasta DATE NOT NULL,
    limite_uso INT NOT NULL DEFAULT 1 -- Veces que puede usarse
);

-- tabla para los cupones usados
CREATE TABLE IF NOT EXISTS aromas2.cupones_uso (

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
CREATE TABLE IF NOT EXISTS aromas2.metodos_pago (

    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS aromas2.estados_pago (
    
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- tabla para los pagos
CREATE TABLE IF NOT EXISTS aromas2.pagos (

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
CREATE TABLE IF NOT EXISTS aromas2.stock (

    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    producto int,
    agotado tinyint,

    CONSTRAINT stock_producto_fk 
    FOREIGN KEY (producto) 
    REFERENCES productos (id)
    ON DELETE CASCADE    
);

-- tabla para almacenar las tirillas de los pagos
CREATE TABLE IF NOT EXISTS aromas2.tirillas (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pago_id INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_generacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT tirillas_pago_fk FOREIGN KEY (pago_id)
    REFERENCES pagos(id)
    ON DELETE CASCADE
);

-- tabla para almacenar las direcciones de envío
CREATE TABLE IF NOT EXISTS aromas2.direcciones_envio (
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
CREATE TABLE IF NOT EXISTS aromas2.log_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario INT,
    accion VARCHAR(255),
    detalle TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario) REFERENCES usuarios(dni)
);

-- tabla para mantener trazabilidad si cambias el precio
CREATE TABLE IF NOT EXISTS aromas2.historial_precios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    precio FLOAT NOT NULL,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATETIME,

    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

CREATE INDEX IF NOT EXISTS index_usuarios ON aromas2.usuarios (userName, email);
CREATE INDEX IF NOT EXISTS index_factura ON aromas2.factura (usuario, fecha);
CREATE INDEX IF NOT EXISTS index_ventas ON aromas2.ventas (factura, producto, fecha);
CREATE INDEX IF NOT EXISTS index_carrito ON aromas2.carrito (usuario);
CREATE INDEX IF NOT EXISTS index_carrito_detalle ON aromas2.carrito_detalle (producto, carrito);
CREATE INDEX IF NOT EXISTS index_pedidos ON aromas2.pedidos (usuario);
CREATE INDEX IF NOT EXISTS index_pedido_detalle ON aromas2.pedido_detalle (pedido);
CREATE INDEX IF NOT EXISTS index_devoluciones ON aromas2.devoluciones (producto, factura, estado);
CREATE INDEX IF NOT EXISTS index_cupones_codigo ON aromas2.cupones (codigo);
CREATE INDEX IF NOT EXISTS index_cupones_tipo ON aromas2.cupones (tipo);
CREATE INDEX IF NOT EXISTS index_cupones_uso_usuario ON aromas2.cupones_uso (usuario);
CREATE INDEX IF NOT EXISTS index__cupones_uso_cupon ON aromas2.cupones_uso (cupon);
CREATE INDEX IF NOT EXISTS index_metodos_pago ON aromas2.metodos_pago (nombre);
CREATE INDEX IF NOT EXISTS index_pagos ON aromas2.pagos (pedido, metodo_pago);

CREATE PROCEDURE `productos mas vendidos`(IN `num` INT)
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
SELECT
    producto,
    SUM(cantidad) AS cantidad
FROM
    ventas
GROUP BY
    producto
ORDER BY
    cantidad DESC
LIMIT 0, num;