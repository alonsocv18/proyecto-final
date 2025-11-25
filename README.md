# Proyecto Final — Documentación

Este repositorio agrupa dos proyectos relacionados:

- `backend-final`: API REST construida con PHP (Slim 2.x) y PDO.
- `frontend-final`: aplicación cliente desarrollada con Angular 20.x.

Este documento describe requisitos, arquitectura, buenas prácticas de seguridad (no incluir secretos en el repositorio) y pasos reproducibles para instalar y ejecutar el sistema en un entorno local.

**Resumen técnico**

- Backend: PHP 7.1.33, `slim/slim` 2.\*.
- Frontend: Angular 20.3.x, TypeScript ~5.9.2, Angular CLI 20.3.x.
- Persistencia: MySQL / MariaDB (PDO MySQL).

**Principios de seguridad aplicados**

- No almacenar credenciales, claves ni endpoints sensibles en el repositorio.
- Usar un archivo `*.env` local (no versionado) o variables de entorno en el servidor/CI.
- Proveer un `*.env.example` con nombres de variables y valores de ejemplo no sensibles.

Si deseas, puedo añadir `backend-final/.env.example` y `backend-final/schema.sql` para facilitar la configuración inicial.

**Requisitos mínimos**

# Proyecto Final — Instalación mínima

Este archivo contiene únicamente tres secciones: requisitos, instalación backend e instalación frontend.

---

1. Requisitos

- Backend:

  - PHP >= 7.1.33
  - Composer 1.10+ / 2.x
  - Extensiones PHP: `pdo`, `pdo_mysql`, `mbstring`, `zip`, `xml`, `gd`
  - MySQL / MariaDB (soporte PDO MySQL)
  - Dependencias del proyecto: `slim/slim` 2.\*, `phpoffice/phpspreadsheet` 1.1.0

- Frontend:
  - Node.js 18.x o 20.x
  - npm (o yarn)
  - Angular CLI 20.x
  - TypeScript ~5.9

---

2. Instalación — Backend

1) Abrir terminal y situarse en el directorio del backend:

```powershell
cd backend-final
```

2. Instalar dependencias PHP con Composer:

```powershell
composer install --no-interaction --prefer-dist
```

3. Proveer las variables de entorno/secretos necesarias fuera del repositorio (por ejemplo, en el gestor de variables del sistema o en el entorno del servidor). No incluir valores en el repositorio.

4. Crear la base de datos y las tablas requeridas (las migraciones no están incluidas en el repositorio).

5. Servir la aplicación mediante su servidor web configurado apuntando a `backend-final/public`, o bien usar el servidor PHP embebido para pruebas locales.

---

3. Instalación — Frontend

1) Abrir terminal y situarse en el directorio del frontend:

```powershell
cd frontend-final
```

2. Instalar dependencias de Node:

```powershell
npm ci
```

3. Ejecutar en modo desarrollo o compilar para producción según necesites:

```powershell
# desarrollo
npm start o ng serve
# compilación
npm run build
```

---

Fin.

```ts

```
