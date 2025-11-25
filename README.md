# Proyecto Final

## Descripción

Aplicación compuesta por una API backend en PHP y un cliente frontend en Angular. El backend gestiona usuarios, productos, categorías y movimientos de stock; el frontend consume la API para administrar y visualizar la información.

## Tecnologías utilizadas

- Backend:
  - PHP (compatible con 7.4 / 8.x)
  - Slim Framework 2.x (`slim/slim`)
  - PhpSpreadsheet (`phpoffice/phpspreadsheet`)
  - PDO (MySQL)
- Frontend:
  - Angular 20.x
  - TypeScript (~5.9)
  - PrimeNG, Tailwind (se encuentran en `package.json`)

## Arquitectura

Backend (`backend-final`):

- `public/index.php`: punto de entrada; carga autoload, middlewares y rutas.
- `app/routes/api.php`: definición de rutas públicas y protegidas (login, status, `/api/productos`, `/api/categorias`, `/api/movimientos`, etc.).
- `app/controllers/`: controladores que gestionan la lógica de entrada y salida HTTP.
- `app/repositories/`: acceso a datos y consultas SQL.
- `app/models/`: representaciones sencillas de entidades.
- `app/middleware/`: autenticación y verificación de token/API key.
- `app/core/Database.php`: manejo de la conexión PDO a la BD.

Frontend (`frontend-final`):

- `src/main.ts`, `src/index.html`, `src/styles.scss`: arranque y assets.
- `src/app/`: módulo principal con submódulos `core/`, `features/`, `shared/`.
- `angular.json` y `package.json`: configuración de build y scripts.

## Instalación

Secciones separadas para backend y frontend. No incluye valores ni secretos; suministra la configuración necesaria en tu entorno (variables del sistema, gestor de secretos o archivos locales no versionados).

Backend

1. Situarse en el directorio del backend:

```powershell
cd backend-final
```

2. Instalar dependencias PHP con Composer:

```powershell
composer install --no-interaction --prefer-dist
```

3. Proveer la configuración necesaria (conexión a base de datos y secretos) fuera del repositorio. Revisa `app/core/Database.php` para conocer las variables de entorno que el proyecto lee.

4. Crear la base de datos y las tablas requeridas. El proyecto no incluye migraciones automatizadas; importa o ejecuta el esquema SQL que uses en tu entorno.

5. Ejecutar la aplicación (opciones):

- Deploy en servidor web (configurar VirtualHost/DocumentRoot a `backend-final/public`).
- Prueba local con PHP embebido:

```powershell
# Desde el directorio backend-final
php -S localhost:8000 -t public
```

FrontEnd

1. Situarse en el directorio del frontend:

```powershell
cd frontend-final
```

2. Instalar dependencias de Node (recomendado `npm ci` para entornos reproducibles):

```powershell
npm ci
```

3. Ejecutar en modo desarrollo:

```powershell
npm start
```

`npm start` ejecuta el script `ng serve` definido en `package.json`. Alternativas:

```powershell
npx ng serve --configuration development
# o si tienes Angular CLI global
ng serve --configuration development
```

4. Compilar para producción:

```powershell
npm run build
```

Notas finales

- Este README documenta la instalación y la arquitectura. No contiene credenciales ni valores sensibles.

Fin.
