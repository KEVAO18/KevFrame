# 🔥 KevFrame - Un Framework PHP Moderno, Seguro y Elegante

[![Versión](https://img.shields.io/badge/version-1.1.0-blue.svg)]() [![Licencia](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE.md) [![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4.svg)]()

> **KevFrame** es un framework PHP ligero y potente, diseñado para el desarrollo rápido y seguro de aplicaciones web. Su arquitectura MVC, combinada con un ORM intuitivo y un motor de plantillas seguro, te permite construir proyectos robustos con un código limpio y mantenible.

## 📋 Tabla de Contenidos

- [✨ Características Principales](#-características-principales)
- [🚀 Inicio Rápido](#-inicio-rápido)
- [📁 Estructura del Proyecto](#-estructura-del-proyecto)
- [⚡ El Corazón de KevFrame: El ORM](#-el-corazón-de-kevframe-el-orm)
- [🛡️ La Seguridad es Primero](#️-la-seguridad-es-primero)
- [🛠️ CLI Inteligente](#️-cli-inteligente)
- [🎨 Motor de Plantillas](#-motor-de-plantillas)
- [🤝 Cómo Contribuir](#-cómo-contribuir)

---

## ✨ Características Principales

✅ **ORM Integrado y Seguro**
- Abstracción completa de la base de datos. ¡Escribe PHP, no SQL!
- Métodos CRUD dinámicos (`all`, `find`, `create`, `update`, `delete`).
- **Protección automática contra Inyección SQL** gracias al uso exclusivo de consultas preparadas.

🔒 **Seguridad por Defecto**
- **Protección CSRF** automática en todas las peticiones `POST`, `PUT` y `DELETE`.
- **Motor de plantillas seguro** que escapa la salida por defecto para prevenir XSS.
- **Renderizador de vistas "enjaulado"** para prevenir ataques de *Path Traversal*.

🛠️ **CLI Inteligente con Introspección**
- Servidor de desarrollo integrado.
- **Generador de modelos automático**: El CLI se conecta a tu base de datos, analiza la estructura de tus tablas y crea los modelos por ti.
- Generadores de código para controladores, componentes y más, usando plantillas personalizables.

🎨 **Motor de Plantillas Expresivo**
- Sintaxis limpia y fácil de aprender (`@if`, `@foreach`, `{{ $variable }}`).
- Directiva `@raw()` para un manejo explícito y seguro de datos sin escapar.
- Sistema de layouts y secciones (`@extends`, `@section`).

---

## 🚀 Inicio Rápido

```bash
# 1. Clona el proyecto
git clone [https://github.com/KEVAO18/KevFrame.git](https://github.com/KEVAO18/KevFrame.git)
cd KevFrame

# 2. Instala las dependencias
composer install

# 3. Configura tu entorno
cp .example.env .env
# (Ajusta la configuración de tu base de datos en .env)

# 4. Inicia el servidor de desarrollo
php kev serve
```

🎉 **¡Listo!** Tu aplicación estará disponible en `http://localhost:8000`

## 📁 Estructura del Proyecto

KevFrame sigue una arquitectura **MVC moderna** con separación clara de responsabilidades. Cada directorio tiene un propósito específico para mantener el código organizado y escalable.

```
KevFrame/
├── 📁 database/
│   ├── 📁 factories/
│   ├── 📁 migrations/
│   └── 📁 seeders/
│
├── 📂 public/
│   ├── 📂 css/
│   ├── 📂 docs/
│   ├── 📂 img/
│   ├── 📂 js/
│   └── 📄 Runner.php
│
├── 📂 src/
│   ├── 📂 Core/
│   │   ├── 📂 Cli/
│   │   │   ├── 📄 Generator.php
│   │   │   └── 📂 Stubs/
│   │   │
│   │   ├── 📄 Cli.php
│   │   ├── 📄 Database.php
│   │   ├── 📄 Request.php
│   │   ├── 📄 Router.php
│   │   ├── 📄 routes.php
│   │   ├── 📄 SessionManager.php
│   │   └── 📄 View.php
│   │
│   ├── 📂 http/
│   │   ├── 📂 controllers/
│   │   │   ├── 📄 ErrorController.php
│   │   │   └── 📄 IndexController.php
│   │   │
│   │   ├── 📂 handlers/
│   │   └── 📂 interfaces/
│   │
│   │
│   ├── 📂 Models/
│   │    └── 📄 Model.php
│   │
│   ├── 📂 Security/
│   │    └── 📄 csrf.php
│   │
│   └── 📂 templates/
│       ├── 📄 KevEngine.php
│       ├── 📄 KevLiteEngine.php
│       ├── 📄 KevTemplateEngine.php
│       └── 📄 TemplateEngineInterface.php
│
├── 📂 web/
│   ├── 📂 componentes/
│   └── 📂 views/
│
├── 📄 .example.env
├── 📄 composer.json
├── 📄 composer.lock
├── 📄 DOCUMENTACION.md
├── 📄 kev
├── 📄 License.md
├── 📄 README.md
└── 📄 serve.php
```

# Ejemplos importantes

## creacion de un modelo

Basandose en que la base de datos usa los nombres de las entidades en plural tales como:

- Usuarios
- Productos
- Categorias
- Credenciales

se crearan los modelos con la siguiente extructura:

```bash
php kev make:model "el nombre de la entidad en singular"
```

De este modo el mini ORM reconocerá el modelo como derivado de la entidad. Ejemplo

Se tiene la tabla:

```sql
    create table totals(

        -- campos: id, tipo, fecha, concepto, monto
        id int(11) auto_increment not null,
        tipo tinyint(1) not null,
        fecha timestamp not null 
        DEFAULT current_timestamp() 
        ON UPDATE current_timestamp(),
        concepto varchar(100) not null,
        monto double not null,

        -- primarias, foraneas e indices
        CONSTRAINT pk_totales PRIMARY KEY (id),

        INDEX (fecha)
    );
```

al ejecutar el comando 

```bash
    php kev make:model total
```

construye el modelo total con la siguiente extructura

```php
<?php

namespace App\Models;

class TotalModel extends Model
{
    /**
     * El nombre de la tabla en la base de datos.
     */
    protected string $table = 'totals';

    /**
     * La clave primaria de la tabla.
     */
    protected string $primaryKey = 'id';

    /**
     * El esquema de la tabla (descubierto automáticamente).
     */
    protected array $fields = [
        'id' => 'int(11)',
        'tipo' => 'tinyint(1)',
        'fecha' => 'timestamp',
        'concepto' => 'varchar(100)',
        'monto' => 'double',
    ];

    /**
     * Define las relaciones del modelo aquí.
     */
    protected array $relations = [];
}

```

Pero si usas un nombre cuya entidad no existe aun en la base de datos tendras que crear la estructura tu mismo teniendo la estructura de el siguiente modo

```php
<?php

namespace App\Models;

class TotalModel extends Model
{
    /**
     * El nombre de la tabla en la base de datos.
     */
    protected string $table = 'totals';

    /**
     * La clave primaria de la tabla.
     */
    protected string $primaryKey = 'id';

    /**
     * El esquema de la tabla (descubierto automáticamente).
     */
    protected array $fields = [
        
    ];

    /**
     * Define las relaciones del modelo aquí.
     */
    protected array $relations = [];
}

```

## 🛤️ Definiendo Rutas

```php
<?php
// src/Core/routes.php

// Ruta GET
$router->get('/', 'IndexController::class', 'home');

// Ruta POST
$router->post('/users', 'UserController::class', 'store');

// Ruta PUT
$router->put('/users/{id}', 'UserController::class', 'update');

// Ruta DELETE
$router->delete('/users/{id}', 'UserController::class', 'destroy');
```

## ⚡ Instalación

### 📜 Requisitos del Sistema

| Componente | Versión Mínima | Recomendada |
|-----------|-----------------|-------------|
| **PHP** | 8.0+ | 8.2+ |
| **Composer** | 2.0+ | 2.5+ |
| **MySQL** | 5.7+ | 8.0+ |

### 🚀 Instalación Rápida

#### 1. **Clonar el repositorio**
```bash
git clone https://github.com/KEVAO18/KevFrame.git
cd KevFrame
```

#### 2. **Verificar requisitos**
```bash
# Verificar versión de PHP
php --version

# Verificar Composer
composer --version
```

#### 3. **Instalar dependencias**
```bash
composer install

# Para desarrollo (incluye herramientas de testing)
composer install --dev

# Para producción (optimizada)
composer install --no-dev --optimize-autoloader
```

#### 4. **Configuración del entorno**
```bash
# Copiar archivo de configuración
cp .example.env .env

# En Windows
copy .example.env .env
```

## 🔧 Configuración

### 🎨 Configuración Básica (.env)

```ini
# ===========================================
#         CONFIGURACIÓN DE APLICACIÓN
# ===========================================
APP_NAME="KevFrame"
APP_ENV=development
APP_HOST=localhost
APP_PORT=8000
APP_BASE_URL="http://${APP_HOST}:${APP_PORT}/"
APP_ICON="${APP_BASE_URL}img/favicon.ico"

# ===========================================
#       CONFIGURACIÓN DE BASE DE DATOS
# ===========================================
DB_HOST=localhost
DB_NAME=db_tienda
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4

# ===========================================
#        RUTAS DE ARCHIVOS ESTÁTICOS
# ===========================================
COMPOSER_FOLDER="${APP_BASE_URL}../vendor/"
PUBLIC_FOLDER="${APP_BASE_URL}"
CSS_FOLDER="${PUBLIC_FOLDER}css/"
JS_FOLDER="${PUBLIC_FOLDER}js/"
IMG_FOLDER="${PUBLIC_FOLDER}img/"
DOCS_FOLDER="${PUBLIC_FOLDER}docs/"

```

### ⚙️ Configuración Avanzada

### 5. **Iniciar el servidor de desarrollo**

> 🌐 **Por defecto, el servidor se inicia en `localhost:8000`. Puedes acceder a tu aplicación en `http://localhost:8000`**.

```bash
# Servidor básico
php kev serve

# Con host y puerto específicos
php kev serve --host=127.0.0.1 --port=8000

```

### 🔧 Comandos CLI Disponibles

```bash
    # Start development server
    php kev serve               
    
    # Create a new controller
    php kev make:controller     
    
    # Create a new model
    php kev make:model          
    
    # Create a new handler
    php kev make:handler        
    
    # Create a new interface
    php kev make:interface      
    
    # Create a new component
    php kev make:component      
    
    # Create a new view
    php kev make:view           
    
    # Show the version of the application
    php kev version             
    
    # Show this help message
    php kev help                
```

## 🏗️ Arquitectura

### 📞 FAQ

**P: ¿Cómo puedo cambiar el motor de plantillas?**
R: En `config/view.php`, cambia el valor de `'engine' => 'KevEngine'` a `'KevLiteEngine'` o `'KevTemplateEngine'`.

**P: ¿Se puede usar con Docker?**
R: Sí, puedes crear un Dockerfile basado en PHP 8.2-apache e incluir las dependencias necesarias.

**P: ¿Cómo optimizar para producción?**
R: Ejecuta `php kev optimize`, configura `APP_ENV=production` y habilita el caché de templates.

**P: ¿Soporta APIs RESTful?**
R: Sí, incluye soporte completo para APIs REST con validación JSON y responses estructuradas.

## 🤝 Contribuir

### 👥 Cómo Contribuir

1. **Fork** el repositorio
2. **Crea** una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. **Commit** tus cambios (`git commit -am 'Add nueva funcionalidad'`)
4. **Push** a la rama (`git push origin feature/nueva-funcionalidad`)
5. **Abre** un Pull Request

### 📜 Guías de Contribución

- Sigue las **convenciones PSR-12** para el estilo de código
- **Documenta** nuevas funcionalidades
- **Incluye tests** para nuevas funcionalidades
- **Mantén compatibilidad** hacia atrás cuando sea posible

### 📝 Reporte de Bugs

Cuando reportes un bug, incluye:
- **Versión** de KevFrame y PHP
- **Pasos** para reproducir el problema
- **Comportamiento esperado** vs **comportamiento actual**
- **Código** mínimo que reproduce el error

## 📄 Licencia

Este proyecto está bajo la **Licencia MIT**. Consulta el archivo [LICENSE.md](LICENSE.md) para más detalles.

---

<div align="center">

### 🔥 **KevFrame - Modern PHP Framework**

**[Documentación](https://kevframe.dev)** • **[GitHub](https://github.com/KEVAO18/KevFrame)** • **[Comunidad](https://discord.gg/kevframe)**

**⭐ Si te gusta KevFrame, ¡dale una estrella en GitHub! ⭐**

</div>
