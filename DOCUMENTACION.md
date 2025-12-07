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
# 1. Crea el proyecto
composer create-project kevao-frame/kevframe

# 2. Instala las dependencias
composer install

# 3. Inicia el servidor de desarrollo
php kev serve
```

🎉 **¡Listo!** Tu aplicación estará disponible en `http://localhost:8000`

## 📁 Estructura del Proyecto

KevFrame sigue una arquitectura **MVC moderna** con separación clara de responsabilidades. Cada directorio tiene un propósito específico para mantener el código organizado y escalable.

```
KevFrame/
├── 📁 database/
│   ├── 📁 factories/
│   │   └── 📄 Factory.php
│   │
│   ├── 📁 seeders/
│   │   └── 📄 Seeder.php
│   │
│   ├── 📁 relations/
│   │   ├── 📄 Relation.php
│   │   ├── 📄 BelongsTo.php
│   │   ├── 📄 HasMany.php
│   │   ├── 📄 HasOne.php
│   │   └── 📄 ManyToMany.php
│   │
│   ├── 📁 migrations/
│   ├── 📄 Blueprint.php
│   └── 📄 Schema.php
│
├── 📂 node_modules/
│
├── 📂 public/
│   ├── 📂 docs/
│   ├── 📂 img/
│   └── 📄 runner.php
│
├── 📂 resources/
│   ├── 📂 css/
│   └── 📂 js/
│
├── 📂 src/
│   ├── 📂 Core/
│   │   ├── 📂 Cli/
│   │   │   ├── 📄 Generator.php
│   │   │   ├── 📄 MakeComponent.php
│   │   │   ├── 📄 DbCommand.php
│   │   │   └── 📂 Stubs/
│   │   │       ├── 📄 Component.php
│   │   │       ├── 📄 controller.php
│   │   │       ├── 📄 factory.php
│   │   │       ├── 📄 handler.php
│   │   │       ├── 📄 interface.php
│   │   │       ├── 📄 migration.php
│   │   │       ├── 📄 model.php
│   │   │       ├── 📄 seeder.php
│   │   │       └── 📄 view.php
│   │   │
│   │   ├── 📄 Cli.php
│   │   ├── 📄 Database.php
│   │   ├── 📄 Helper.php
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
├── 📂 vendor/
│
├── 📂 web/
│   ├── 📂 componentes/
│   │   ├── 📂 errors/
│   │   │   ├── 📄 404Component.php
│   │   │   └── 📄 GeneralErrorComponent.php
│   │   │
│   │   └── 📂 main/
│   │       ├── 📄 HomeComponent.php
│   │       └── 📄 PruebasComponent.php
│   │
│   └── 📂 views/   
│       └── 📄 main.php
│
├── 📄 .env
├── 📄 composer.json
├── 📄 composer.lock
├── 📄 DOCUMENTACION.md
├── 📄 kev
├── 📄 License.md
├── 📄 package-lock.json
├── 📄 package.json
├── 📄 README.md
├── 📄 serve.php
└── 📄 vite.config.js
```

# Ejemplos importantes

## creacion de un modelo

Primero debes crear la tabla en la base de datos creando una migracion

```bash
php kev make:migration "Creacion_tabla_Usuarios" --tabla=usuarios
```

resultando en la siguiente estructura en la ruta database/migrations

```php

<?php

use App\Database\Schema;
use App\Database\Blueprint;

/**
 * Migración para la tabla usuarios.
 * Generada el: 2025_12_06_101558
 */
return new class
{
    /**
     * Ejecuta la migración para construir el esquema.
     * Aquí es donde defines la estructura de tu tabla.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('dni');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('pass');
            $table->integer('rol');

            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     * Generalmente, esto implica eliminar la tabla.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};


```

Luego debes crear el modelo

```bash
php kev make:model "Usuarios"
```

de esta manera crearas un modelo con la estructura de tu tabla usuarios en la ruta src/Models

```php
<?php

namespace App\Models;

class UsuariosModel extends Model
{
    /**
     * El nombre de la tabla en la base de datos.
     */
    protected string $table = 'usuarios';

    /**
     * La clave primaria de la tabla.
     */
    protected string $primaryKey = 'dni';

    /**
     * El esquema de la tabla (descubierto automáticamente).
     */
    protected array $fields = [
        'dni' => 'int(11)',
        'nombre' => 'varchar(255)',
        'apellido' => 'varchar(255)',
        'email' => 'varchar(255)',
        'pass' => 'varchar(255)',
        'rol' => 'int(11)',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * Define las relaciones del modelo aquí.
     */
    protected array $relations = [];
}

?>
```

## 🛤️ Definiendo Rutas

```php
<?php
// src/Core/routes.php

// Ruta GET
$router->get('/', IndexController::class, 'index');

// Ruta POST
$router->post('/users', IndexController::class, 'store');

// Ruta PUT
$router->put('/users/{id}', IndexController::class, 'update');

// Ruta DELETE
$router->delete('/users/{id}', IndexController::class, 'delete');
```

## ⚡ Instalación

### 📜 Requisitos del Sistema

| Componente | Versión Mínima | Recomendada |
|-----------|-----------------|-------------|
| **PHP** | 8.0+ | 8.2+ |
| **Composer** | 2.0+ | 2.5+ |
| **MySQL** | 5.7+ | 8.0+ |

## 🔧 Configuración

### 🎨 Configuración Básica (.env)

```ini
APP_NAME="KevFrame"                              # nombre de la aplicación
APP_ENV=development                              # entorno de despliegue (development o production)
APP_HOST=localhost                               # host de la aplicación
APP_PORT=8000                                    # puerto de la aplicación
APP_BASE_URL="http://${APP_HOST}:${APP_PORT}/"   # URL base de la aplicación
APP_ICON="${APP_BASE_URL}public/img/logo.png"    # ícono de la aplicación
APP_DB_DRIVER=mysql                              # driver de la base de datos (mysql, sqlsrv, sqlite)

# Configuración de la base de datos MySQL
DB_HOST=localhost                                # host de la base de datos
DB_NAME=kevframe                                 # nombre de la base de datos
DB_USER=root                                     # usuario de la base de datos
DB_PASS=                                         # contraseña de la base de datos
DB_CHARSET=utf8mb4                               # conjunto de caracteres de la base de datos

# Configuración de la base de datos SQLServer
# DB_SQLSERVER_HOST=127.0.0.1\SQLEXPRESS           # host de la base de datos SQLServer
# DB_SQLSERVER_NAME=kevframe                       # nombre de la base de datos SQLServer
# DB_SQLSERVER_USER=sa                             # usuario de la base de datos SQLServer
# DB_SQLSERVER_PASS=MiPasswordFuerte123            # contraseña de la base de datos SQLServer

# Configuración de la base de datos SQLite
# DB_SQLITE_PATH=./storage/database.sqlite   # Define la ruta de tu archivo de base de datos

COMPOSER_FOLDER="${APP_BASE_URL}vendor/"         # carpeta de instalación de Composer
PUBLIC_FOLDER="${APP_BASE_URL}public/"           # carpeta pública de la aplicación
CSS_FOLDER="${PUBLIC_FOLDER}css/"                # carpeta de estilos CSS
JS_FOLDER="${PUBLIC_FOLDER}js/"                  # carpeta de scripts JS
IMG_FOLDER="${PUBLIC_FOLDER}img/"                # carpeta de imágenes
DOCS_FOLDER="${PUBLIC_FOLDER}docs/"              # carpeta de documentación


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

### 📞 FAQ

**P: ¿Cómo puedo cambiar el motor de plantillas?**
R: En `config/view.php`, cambia el valor de `'engine' => 'KevEngine'` a `'KevLiteEngine'` o `'KevTemplateEngine'`.

**P: ¿Se puede usar con Docker?**
R: Sí, puedes crear un Dockerfile basado en PHP 8.2-apache e incluir las dependencias necesarias.

**P: ¿Soporta APIs RESTful?**
R: Sí, aunque aun no esta totalmente implementado.

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
