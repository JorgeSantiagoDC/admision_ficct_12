# 🎓 Sistema de Admisión FICCT

Sistema web para la gestión del proceso de admisión de la **Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones (FICCT)** de la Universidad Autónoma Gabriel René Moreno (UAGRM).

---

## 🛠️ Stack Tecnológico

| Tecnología | Versión |
|-----------|---------|
| PHP | 8.x |
| Laravel | 12.x |
| PostgreSQL | 18.x |
| Bootstrap | 5.3 |
| Railway | Producción |

---

## 📋 Requisitos del entorno local

- PHP 8.x con extensiones: `pdo_pgsql`, `pgsql`, `mbstring`, `xml`, `intl`
- Composer
- PostgreSQL 15+
- Git

---

## ⚙️ Instalación local

### 1. Clona el repositorio

```bash
git clone https://github.com/JorgeSantiagoDC/admision_ficct_12.git
cd admision_ficct_12
```

### 2. Instala dependencias

```bash
composer install
```

### 3. Configura el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` con tus datos locales:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=admision_ficct_db
DB_USERNAME=postgres
DB_PASSWORD=tu_password

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 4. Crea la base de datos en PostgreSQL

```sql
CREATE DATABASE admision_ficct_db OWNER postgres;
```

### 5. Ejecuta las migraciones y seeders

```bash
php artisan migrate --seed
```

### 6. Inicia el servidor local

```bash
php artisan serve
```

Accede en: `http://localhost:8000`

---

## 👤 Usuarios de prueba

| Usuario | Contraseña | Rol |
|---------|-----------|-----|
| `admin_ficct` | `admin123` | Administrador |
| `docente_ficct` | `docente123` | Docente |
| `postulante_ficct` | `postulante123` | Postulante |

---

## 🗄️ Estructura de la Base de Datos

El sistema cuenta con **12 tablas en Tercera Forma Normal (3FN)**:

```
rol
└── usuario
    ├── docente
    │   └── grupo
    │       ├── examen
    │       │   └── nota
    │       └── inscripcion_grupo
    └── postulante
        ├── requisito
        ├── pago
        ├── inscripcion_grupo
        └── nota
carrera (referenciada por postulante)
materia (referenciada por grupo)
```

### Reglas de negocio implementadas (Triggers PostgreSQL)

| # | Trigger | Descripción |
|---|---------|-------------|
| 1 | `tg_pago_aprobado` | Pago completado → estado_admision = 'En Proceso' |
| 2 | `tg_validar_opciones_carrera` | Opción 1 ≠ Opción 2 de carrera |
| 3 | `tg_validar_edad_postulante` | Edad mínima 16 años |
| 4 | `tg_capacidad_grupo` | Máximo 70 estudiantes por grupo |
| 5 | `tg_validar_nota` | Calificación entre 0 y 100 |
| 6 | `tg_verificar_cupo_carrera` | Respeta cupo máximo por carrera |
| 7 | `tg_recalcular_promedio` | Recalcula promedio final automáticamente |
| 8 | `tg_correo_postulante` | Correo único por postulante |
| 9 | `tg_limite_grupos_docente` | Máximo 4 grupos por docente por gestión |
| 10 | `tg_estado_admision_automatico` | Aprobado si promedio ≥ 60, Reprobado si < 60 |

---

## 🚀 Despliegue en Railway

El proyecto está desplegado en **Railway** con PostgreSQL como base de datos en la nube.

### Variables de entorno requeridas en Railway

```env
APP_NAME=Admision FICCT
APP_ENV=production
APP_KEY=tu_app_key
APP_DEBUG=false
APP_URL=https://tu-dominio.railway.app

DB_CONNECTION=pgsql
DB_HOST=postgres.railway.internal
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=tu_password_railway

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

---

## 🌿 Flujo de trabajo con Git

### Estructura de ramas

```
main              ← Producción
└── develop       ← Integración (Railway despliega desde aquí)
    ├── jorgesantiago    ← Rama de Jorge Santiago
    └── diegoalejandro   ← Rama de Diego Alejandro
```

### Flujo diario de desarrollo

```bash
# 1. Actualiza tu rama con los últimos cambios
git checkout develop
git pull origin develop
git checkout jorgesantiago   # o diegoalejandro
git merge develop

# 2. Trabaja y guarda cambios
git add .
git commit -m "feat: descripcion del cambio"
git push origin jorgesantiago

# 3. Cuando terminas un caso de uso
# → Crea un Pull Request en GitHub: tu-rama → develop
```

### Convención de commits

| Prefijo | Uso |
|---------|-----|
| `feat:` | Nueva funcionalidad |
| `fix:` | Corrección de error |
| `chore:` | Tareas de mantenimiento |
| `docs:` | Documentación |
| `refactor:` | Refactorización de código |

---

## 📁 Estructura del proyecto

```
admision_ficct_12/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Auth/
│   │   │       └── LoginController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   └── Models/
│       ├── Rol.php
│       ├── Usuario.php
│       ├── Carrera.php
│       ├── Materia.php
│       ├── Docente.php
│       ├── Postulante.php
│       ├── Requisito.php
│       ├── Pago.php
│       ├── Grupo.php
│       ├── InscripcionGrupo.php
│       ├── Examen.php
│       └── Nota.php
├── database/
│   ├── migrations/
│   │   ├── 2026_01_01_000001_01_create_rol_table.php
│   │   ├── 2026_01_01_000002_02_create_usuario_table.php
│   │   ├── 2026_01_01_000003_03_create_carrera_table.php
│   │   ├── 2026_01_01_000004_04_create_materia_table.php
│   │   ├── 2026_01_01_000005_05_create_docente_table.php
│   │   ├── 2026_01_01_000006_06_create_postulante_table.php
│   │   ├── 2026_01_01_000007_07_create_requisito_table.php
│   │   ├── 2026_01_01_000008_08_create_pago_table.php
│   │   ├── 2026_01_01_000009_09_create_grupo_table.php
│   │   ├── 2026_01_01_000010_10_create_inscripcion_grupo_table.php
│   │   ├── 2026_01_01_000011_11_create_examen_table.php
│   │   ├── 2026_01_01_000012_12_create_nota_table.php
│   │   └── 2026_01_01_000013_13_create_triggers_admision.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── UsuarioSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── admin/
│       │   └── dashboard.blade.php
│       ├── docente/
│       │   └── dashboard.blade.php
│       └── postulante/
│           └── dashboard.blade.php
├── routes/
│   └── web.php
├── nixpacks.toml
└── README.md
```

---

## 👥 Equipo de desarrollo

| Nombre | Rama | Rol |
|--------|------|-----|
| Jorge Santiago | `jorgesantiago` | Desarrollador |
| Diego Alejandro | `diegoalejandro` | Desarrollador |

---

## 📚 Metodología

Este proyecto sigue la metodología **PUDS (Proceso Unificado de Desarrollo de Software)**, organizado en ciclos de desarrollo con casos de uso priorizados.

### Ciclo #1 — 15 Casos de Uso

| CU | Nombre | Estado |
|----|--------|--------|
| CU1 | Iniciar Sesión | ✅ Implementado |
| CU2 | Cerrar Sesión | ✅ Implementado |
| CU3 | Gestionar Postulantes | ⏳ Pendiente |
| CU4 | Subir Requisitos Documentales | ⏳ Pendiente |
| CU5 | Validar Requisitos Documentales | ⏳ Pendiente |
| CU6 | Consultar Estado de Inscripción | ⏳ Pendiente |
| CU7 | Registrar Preferencia de Carrera | ⏳ Pendiente |
| CU8 | Registrar Pago de Inscripción | ⏳ Pendiente |
| CU9 | Validar Pago | ⏳ Pendiente |
| CU10 | Gestionar Materias de Admisión | ⏳ Pendiente |
| CU11 | Administrar Gestiones Académicas | ⏳ Pendiente |
| CU12 | Gestionar Docentes | ⏳ Pendiente |
| CU13 | Calcular Cantidad de Grupos | ⏳ Pendiente |
| CU14 | Crear Grupos Académicos | ⏳ Pendiente |
| CU15 | Asignar Docente a Grupo | ⏳ Pendiente |

---

*UAGRM — FICCT © 2026*
