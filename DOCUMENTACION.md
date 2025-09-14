# Documentación del Proyecto `KEVAO18/KevFrame`

Este documento describe la estructura y los componentes principales del proyecto `KEVAO18/KevFrame`.

## Estructura de Archivos y Directorios

El proyecto sigue una estructura organizada para facilitar el desarrollo y mantenimiento. A continuación, se detalla la disposición de los directorios principales:

```
.example.env
.gitignore
DOCUMENTACION.md
LICENSE.md
README.md
composer.json
composer.lock
http\
├── controllers\
│   ├── ErrorController.php
│   └── IndexController.php
├── handlers\
└── interfaces\
kev
public\
├── css\
│   ├── color.css
│   ├── content.css
│   ├── fonts.css
│   ├── hw.css
│   ├── margin.css
│   ├── padding.css
│   ├── principal.css
│   ├── reset.css
│   └── text.css
├── docs\
├── img\
├── js\
│   └── main.js
└── runner.php
serve.php
src\
├── Core\
│   ├── Cli.php
│   ├── Database.php
│   ├── Request.php
│   ├── Router.php
│   ├── SessionManager.php
│   ├── View.php
│   └── routes.php
├── Templates\
│   ├── KevEngine.php
│   ├── KevLiteEngine.php
│   ├── KevTemplateEngine.php
│   ├── KevLiteEngine.php
│   ├── KevEngine.php
│   └── TemplateEngineInterface.php
└── models\
web\
├── componentes\
│   ├── errors\
│       ├── 404.php
│       └── GeneralError.php
│   └── main\
        └── HomeComponent.php
└── views\
    └── main.php
```

### Componentes Principales

-   **`http/controllers/`**: Contiene la lógica de negocio para las diferentes rutas de la aplicación. Cada archivo PHP dentro de este directorio es un controlador que maneja las solicitudes HTTP.
-   **`http/handlers/`**: Contiene los manejadores de eventos para las diferentes rutas de la aplicación. Cada archivo PHP dentro de este directorio es un manejador que se ejecuta cuando se activa un evento específico.
-   **`http/interfaces/`**: Contiene las interfaces que definen los métodos que deben implementar los controladores y manejadores.
-   **`public/`**: Es el directorio raíz del servidor web. Contiene todos los activos estáticos como hojas de estilo CSS, scripts JavaScript, imágenes y otros archivos que deben ser accesibles directamente desde el navegador.
-   **`src/Core/`**: Este directorio alberga las clases fundamentales que forman el núcleo de la aplicación. Incluye componentes para la gestión de la base de datos, enrutamiento, manejo de solicitudes y sesiones, entre otros.
-   **`src/models/`**: Aquí se definen los modelos de datos, que interactúan con la base de datos y representan la estructura de los datos de la aplicación.
-   **`web/Views/`**: Contiene las plantillas de vista (HTML o PHP con incrustaciones de HTML) que se utilizan para renderizar la interfaz de usuario. `main.php` es la plantilla principal de ejemplo.
-   **`web/componentes/`**: Contiene los componentes de la interfaz de usuario.

## Instalación

Sigue estos pasos para poner en marcha el proyecto:

### 1.  **Clona el repositorio**:
    ```bash
    git clone https://github.com/KEVAO18/KevFrame.git
    cd KevFrame
    ```


### 2.  **Instala las dependencias de Composer**:
    ```bash
    composer install
    ```

### 3. Configuración

El proyecto utiliza un archivo `.env` para la configuración de variables de entorno. Asegúrate de copiar `.example.env` a `.env` y ajustar los valores según sea necesario.

```bash
cp .example.env .env
```

Edita el archivo `.env` para configurar la base de datos, el host y el puerto del servidor de desarrollo, y otras opciones:

```ini
APP_NAME="KevFrame"
APP_DIRECTORY=""
APP_ENV=dev
APP_HOST=localhost
APP_PORT=8000
APP_BASE_URL="http://${APP_HOST}:${APP_PORT}/"
APP_ICON="${APP_BASE_URL}img/favicon.ico"

DB_HOST=localhost
DB_NAME=db_tienda
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4

COMPOSER_FOLDER="${APP_BASE_URL}../vendor/"
PUBLIC_FOLDER="${APP_BASE_URL}"
CSS_FOLDER="${PUBLIC_FOLDER}css/"
JS_FOLDER="${PUBLIC_FOLDER}js/"
IMG_FOLDER="${PUBLIC_FOLDER}img/"
DOCS_FOLDER="${PUBLIC_FOLDER}docs/"


```

### 4. Uso

#### Iniciar el servidor de desarrollo

Para iniciar el servidor de desarrollo, ejecuta el siguiente comando en la raíz del proyecto:

```bash
php kev serve
```

El servidor se iniciará en `http://localhost:8000` por defecto, o en el host y puerto especificados en tu archivo `.env` (`APP_HOST` y `APP_PORT`).

También puedes especificar el host y el puerto directamente en la línea de comandos, lo cual tendrá prioridad sobre la configuración del `.env`:

```bash
php kev serve --host=<host o ip> --port=<puerto a usar>
```

Una vez iniciado el servidor, puedes acceder a la aplicación en tu navegador web.

## 3. Licencia
Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE.md](LICENSE.md) para más detalles.