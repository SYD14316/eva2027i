# DOCUMENTACIÓN DEL SISTEMA eva2027i
## SISTEMA DE EVALUACIÓN DOCENTE - UNIVERSIDAD MARISTA DE GUADALAJARA

---

### INFORMACIÓN GENERAL DEL PROYECTO

**Nombre del Proyecto:** eva2027i  
**Descripción:** Sistema de Evaluación del Desempeño Docente y Servicios  
**Ciclo Académico:** 2026-II  
**Institución:** Universidad Marista de Guadalajara / Educación Superior Marista, A.C.  
**Repositorio:** https://github.com/SYD14316/eva2027i.git  
**Directorio Base:** eva2027i  
**Comentarios:** En esta ciclo somanete se realizara la evaluacion en nivel de licenciaturas, no de preparatoria  

---

### ARQUITECTURA DEL SISTEMA

#### 1. CONFIGURACIÓN BASE (config.php)
- **Base de Datos:** eva2027i (producción: maristamx_eva2027i)
- **Servidor:** localhost  
- **Usuario desarrollo:** root / **Usuario producción:** maristamx_evaluaciones
- **Zona Horaria:** America/Mexico_City
- **Ciclo configurado:** 2026-II LICENCIATURA
- **Periodo SANDBOX (activo):** 01 enero - 31 diciembre 2026
- **Periodo PRODUCCIÓN (comentado):** 17 abril - 26 de abril del 2026
- **Máximo caracteres respuestas abiertas:** 700
- **Número de aplicación:** 1

#### 2. ESTRUCTURA DE DIRECTORIOS

```
eva2027i/
├── lib/                    # Librerías y configuración
│   ├── config.php         # Configuración principal
│   ├── functions.php      # Funciones auxiliares PHP
│   ├── functions.js       # Funciones JavaScript (encriptar/desencriptar AES-256)
│   ├── datatables.php     # Configuración DataTables
│   ├── css/              # Estilos CSS (marista.css)
│   ├── img/              # Imágenes del sistema (logos, favicon)
│   ├── purifier/         # Librería HTMLPurifier
│   └── self/             # Librerías personalizadas (self_form_sender, self_ncrptcn)
├── vendor/                # Plantillas HTML reutilizables
│   ├── header.php        # Cabecera HTML con CSS/JS (Bootstrap 4.6.1, jQuery 3.6.3)
│   ├── head.php          # Meta tags y estilos adicionales
│   ├── head2.php         # Variante de cabecera
│   └── header_impresion.php # Cabecera para impresión
├── view/                  # Vistas (en uso)
├── reportes/             # Módulo de reportes internos
├── Documentos/           # Documentación adicional y esquemas
└── *.php                 # Archivos PHP principales
```

---

### NIVELES EDUCATIVOS Y USUARIOS

#### LICENCIATURA  
- **Evaluadores:** Alumnos, Profesores, Coordinadores, FIDCO, Vicerrector, Inglés
- **Evaluados:** Profesores, Coordinadores, Materias Institucionales, ERYS, FIDCO, Inglés, Deporte y Cultura

#### NIVELES DE ACCESO (nivel_acceso en BD)
| Nivel | Descripción |
|---|---|
| ALUMNO | Evalúa profesores y coordinadores |
| PROFESOR | Autoevaluación y evaluación de coordinadores |
| COORDINADOR | Autoevaluación, evaluación de profesores y acceso a reportes |
| VICERRECTOR | Evaluación y acceso a reportes generales |
| FIDCO | Evaluación de Formación Integral |
| INGLES | Evaluaciones del área de inglés |
| ACADEMICO | Acceso a reportes académicos (bach. + lic.) |
| DIRECTOR | Acceso a reportes de dirección |
| REVISOR | Acceso de revisión |
| ADMINISTRADOR | Administración del sistema |
| SUPERUSUARIO | Acceso total al sistema |

---

### TIPOS DE EVALUACIÓN

#### 1. EVALUACIÓN DE PROFESORES
**Archivos principales:**
- `lic_profesores_por_alumnos.php` - Evaluación por alumnos
- `lic_profesores_por_coordinadores.php` - Evaluación por coordinadores  
- `lic_profesores_general.php` - Autoevaluación docente

**Competencias evaluadas:**
- Competencias disciplinares
- Competencias didácticas
- Competencias de evaluación
- Competencias de gestión
- Competencias sociales
- Comentarios generales

#### 2. EVALUACIÓN DE COORDINADORES
**Archivos principales:**
- `lic_coordinadores_por_alumnos.php` - Evaluación por alumnos
- `lic_coordinadores_por_profesores.php` - Evaluación por profesores
- `lic_coordinadores_por_coordinadores.php` - Autoevaluación
- `lic_coordinadores_por_jefes.php` - Evaluación por jefes

**Áreas evaluadas:**
- Seguimiento académico
- Organización de eventos formativos
- Comunicación institucional
- Resolución de conflictos
- Liderazgo y gestión

#### 3. EVALUACIÓN DE MATERIAS INSTITUCIONALES
**Archivos principales:**
- `lic_materiasi_por_alumnos.php` - Evaluación por alumnos
- `lic_materiasi_por_coordinadores.php` - Autoevaluación coordinadores
- `lic_materiasi_por_profesores.php` - Evaluación por profesores

#### 4. EVALUACIONES ESPECIALIZADAS
- **ERYS (Espacios, Recursos y Servicios):** `lic_erys.php`, `lic_erys_profesores.php`
- **FIDCO (Formación Integral):** `lic_fidco.php`
- **Inglés:** `lic_ingles.php`
- **Deporte y Cultura:** `lic_deporteycultura_*.php`

---

### SISTEMA DE ACCESO Y AUTENTICACIÓN

#### Archivos de acceso:
- `index.php` - Página principal de login (método clásico: matrícula + contraseña)
- `login.php` - Login con SSO externo (sistema "Benito" vía parámetro `resultadoingreso`)
- `login_dyc.php` - Login específico para Deporte y Cultura
- `salir.php` - Cierre de sesión

#### Métodos de autenticación:
1. **Login clásico** (`index.php`): Matrícula + contraseña encriptada con `MD5(SHA1(clave))`, consulta directa a tabla `participantes`.
2. **SSO externo** (`login.php`): Recibe parámetro `resultadoingreso` encriptado desde sistema externo "Benito", desencriptado con función `desencriptar_ligero()`. Excluye carreras de "DEPORTE Y CULTURA".
3. **Login DyC** (`login_dyc.php`): Flujo SSO específico para la carrera de Deporte y Cultura.

#### Variables de sesión principales:
- `zez_a_id` - ID del participante
- `zez_a_nivel` - Nivel educativo (LICENCIATURA / BACHILLERATO)
- `zez_a_nivel_acceso` - Rol del usuario
- `zez_a_matricula` - Matrícula
- `zez_a_nombre` - Nombre completo
- `zez_a_carrera` - Carrera
- `zez_a_ciclo` - Ciclo académico activo
- `zez_a_sello` - Indicador de carrera sello

---

### SISTEMA DE REPORTES

#### Reportes raíz del proyecto (`/*.php`):
**Participación:**
- `reporte_participacion_alumnos.php` / `reporte_participacion_alumnos_impresora.php`
- `reporte_participacion_coordinadores.php`
- `reporte_participacion_profesores.php`

**Profesores Licenciatura:**
- `reporte_profesores_lic.php` / `reporte_profesores_lic_impresora.php`
- `reporte_profesores_lic_tabla.php` / `reporte_profesores_lic_tabla_impresora.php`
- `reporte_resultado_profesores.php`

**Coordinadores Licenciatura:**
- `reporte_coordinadores_lic_detalle.php` / `reporte_coordinadores_lic_detalle_impresora.php`
- `reporte_coordinadores_lic_tabla.php`
- `reporte_coordinador_a2.php`

**Evaluaciones especializadas:**
- `reporte_erys_lic.php` / `reporte_erys_lic_impresora.php`
- `reporte_erys_lic_profesores.php` / `reporte_erys_lic_profesores_impresora.php`
- `reporte_fidco_lic.php` / `reporte_fidco_lic_impresora.php`
- `reporte_ingles_lic.php` / `reporte_ingles_lic_impresora.php`

**Reportes A1/A2 (carreras sello):**
- `reporte_a1.php`, `reporte_a2.php`
- `reporte_carrera_a1.php`
- `reporte_resultado_general_a1.php`
- `reporte_resultado_carrera.php`
- `reporte_resultado_sello_a1.php`
- `reporte_general_a2_comparativa.php`

#### Reportes por nivel (bachillerato `bach_reporte_*.php`):
- `bach_reporte_alumnos.php`
- `bach_reporte_coordinadores.php`
- `bach_reporte_general.php`
- `bach_reporte_profesores.php` / `bach_reporte_profesores_impresora.php`
- `bach_reporte_profesores2.php` / `bach_reporte_profesores2_impresora.php`
- `bach_reporte_titulares.php` / `bach_reporte_titulares_impresora.php`

#### Módulo interno `/reportes/`:
- `concentrado.php` - Reporte concentrado general
- `autoevaluacion.php` - Reportes de autoevaluación
- `coordinadores_alumnos.php` - Evaluación coordinadores por alumnos
- `profesores_alumnos.php` - Evaluación profesores por alumnos
- `profesores_coordinadores.php` - Evaluación profesores por coordinadores
- `reporte_profesor_cc.php` / `reporte_profesor_cc_carrera.php` - Con coordinadores
- `reporte_profesor_sc.php` / `reporte_profesor_sc_carrera.php` - Sin coordinadores
- `listado_docentes.php` / `listado_docentes_carrera.php` - Listados
- `reporte_subd.php` - Reporte subdirección
- `mi_alumnos.php`, `ejecucion.php`, `funciones.php`, `sql.php`, `connection.php`, `index.php`

---

### PROCESAMIENTO DE EVALUACIONES

#### Archivos de procesamiento:
- `procesa.php` - Procesamiento general
- `evalua.php` - Interfaz de evaluación
- `evalua_dyc.php` - Evaluación deporte y cultura

#### Características técnicas:
- Sanitización HTML con HTMLPurifier (función `limpiar_campo()`)
- Encriptación AES-256-CBC con OpenSSL para parámetros de evaluación (funciones `encriptar()` / `desencriptar()`)
- Encriptación ligera para SSO (función `desencriptar_ligero()`)
- Hash de contraseñas: `MD5(SHA1(clave))`
- Control de fechas de evaluación por nivel y rol
- Detección y registro de IP de cliente (`obtener_ip_cliente()`)
- Control de sesiones PHP
- Límite de 700 caracteres en respuestas abiertas

---

### FUNCIONALIDADES AUXILIARES

#### Gestión de usuarios:
- `mis_evaluaciones.php` - Panel de evaluaciones pendientes
- `mis_reportes.php` - Acceso a reportes personales
- `grupos.php` - Gestión de grupos
- `coordinadores.php` - Gestión de coordinadores

#### Utilidades:
- `datatables.php` - Tablas de datos interactivas
- `todos.php` - Vista general del sistema
- `logros.php` - Sistema de logros
- `no_iniciada.php` - Evaluación no iniciada
- `cerrada.php` - Evaluación cerrada
- `error_ingreso.php` - Manejo de errores

---

### CARACTERÍSTICAS TÉCNICAS

#### Base de datos:
- **Motor:** MySQL/MariaDB
- **Codificación:** UTF-8
- **Tablas principales:**
  - `participantes` - Usuarios del sistema
  - `carreras` - Carreras con datos de coordinador y sello
  - `lic_profesores_por_alumnos` - Evaluaciones de profesores por alumnos
  - `lic_profesores_por_coordinadores` - Evaluaciones de profesores por coordinadores
  - `lic_coordinadores_por_alumnos` - Evaluaciones de coordinadores por alumnos
  - `bach_evaluaciones_*` - Evaluaciones bachillerato

#### Seguridad:
- Sanitización de entradas con HTMLPurifier (`limpiar_campo()`)
- Encriptación AES-256-CBC (OpenSSL) para parámetros de formularios internos
- Hash de contraseñas: `MD5(SHA1(clave))`
- SSO mediante token encriptado desde sistema externo "Benito"
- Control de sesiones PHP con variables prefijo `zez_a_`
- Validación de fechas de evaluación por rol/nivel
- Registro de IP de acceso

#### Tecnologías utilizadas:
- **Backend:** PHP 7+
- **Base de datos:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 4.6.1
- **JS:** jQuery 3.6.3
- **Íconos:** FontAwesome 5.7.0
- **Librerías PHP:** HTMLPurifier, OpenSSL
- **Plantillas CSS:** marista.css (hoja de estilos institucional)

---

### FLUJO DE EVALUACIÓN

1. **Autenticación:** Login con matrícula y contraseña
2. **Verificación:** Validación de fechas y permisos
3. **Selección:** Elección de evaluación pendiente
4. **Evaluación:** Completar formulario (escala 1-10 + comentarios)
5. **Procesamiento:** Validación y almacenamiento en BD
6. **Confirmación:** Mensaje de evaluación completada
7. **Reportes:** Generación de reportes según rol

---

### PERIODOS DE EVALUACIÓN

#### Configuración SANDBOX (activa en desarrollo):
Todas las fechas: 01 enero - 31 diciembre 2026

#### Configuración PRODUCCIÓN (comentada en config.php):
**Fechas activas:** 22 septiembre - 31 diciembre 2025

| Nivel | Rol | Inicio | Fin |
|---|---|---|---|
| LICENCIATURA | ALUMNO | 22/sep/2025 | 31/dic/2025 |
| LICENCIATURA | PROFESOR | 22/sep/2025 | 31/dic/2025 |
| LICENCIATURA | COORDINADOR | 22/sep/2025 | 31/dic/2025 |
| LICENCIATURA | FIDCO | 22/sep/2025 | 31/dic/2025 |
| LICENCIATURA | VICERRECTOR | 22/sep/2025 | 31/dic/2025 |

---

### CONTACTO Y SOPORTE

**Institución:** Universidad Marista de Guadalajara / Educación Superior Marista, A.C.  
**Sistema:** EVA - Evaluación del Desempeño Docente  

---

### NOTAS DE DESARROLLO

- Versión de aplicación: 1 (NUMERO_APLICACION en config.php)
- Tiempo de impresión reportes: 3000ms
- El archivo `mis_evaluaciones_vjo.php` es la versión anterior de `mis_evaluaciones.php`
- Manual de usuario: `manual_ingreso.pdf` (en raíz del proyecto)
- README disponible en repositorio (contiene nota: "eva2027i - Base para evaluación docente")
- En `/Documentos/`: esquemas de evaluación en .docx y .xlsx, archivo `id.txt`, carpeta `Informacion/`
- El `datatables.php` existe tanto en la raíz como en `/lib/` (configuración de DataTables)

---

**Fecha de documentación:** 17 de marzo de 2026  
**Estado del sistema:** En desarrollo/SANDBOX para ciclo 2026-ii  
**Última actualización:** Revisión y corrección de información institucional - Universidad Marista de Guadalajara

---

<!-- AUTO_COMMIT_HISTORY_START -->

### HISTORIAL RECIENTE DE COMMITS (AUTOMATICO)

**Fecha de corte del historial:** 25 de mayo de 2026

#### 1) Commit `78d83b1` - Se agregan comentarios al reporte integral del docente
- **Autor:** Tu Nombre
- **Fecha:** 2026-05-25 10:20:45 -0600
- **Acciones realizadas:**
  - Mensaje del commit: Se agregan comentarios al reporte integral del docente.
  - Se modificaron 1 archivo(s).
- **Archivos afectados:**
  - `reportes/reporte_profesor_integral.php` (M)

#### 2) Commit `cf1f1d2` - Correccion de filtros por carrera
- **Autor:** Tu Nombre
- **Fecha:** 2026-05-20 09:00:44 -0600
- **Acciones realizadas:**
  - Mensaje del commit: Correccion de filtros por carrera.
  - Se modificaron 7 archivo(s).
- **Archivos afectados:**
  - `Documentos/DOCUMENTACION_SISTEMA_EVA2026I.md` (M)
  - `reportes/reporte_carrera.php` (M)
  - `reportes/reporte_profesor_carrera_cc.php` (M)
  - `reportes/reporte_profesor_carrera_sc.php` (M)
  - `reportes/reporte_profesor_integral.php` (M)
  - `reportes/reporte_profesor_materia_cc.php` (M)
  - `reportes/reporte_profesor_materia_sc.php` (M)

#### 3) Commit `024ecb9` - Correccion de sentencias faltantates
- **Autor:** Tu Nombre
- **Fecha:** 2026-05-19 10:21:06 -0600
- **Acciones realizadas:**
  - Mensaje del commit: Correccion de sentencias faltantates.
  - Se modificaron 2 archivo(s).
- **Archivos afectados:**
  - `Documentos/DOCUMENTACION_SISTEMA_EVA2026I.md` (M)
  - `reportes/reporte_profesor_carrera_sc.php` (M)

#### 4) Commit `06681da` - Correccion de sentencias faltantates
- **Autor:** Tu Nombre
- **Fecha:** 2026-05-19 10:16:18 -0600
- **Acciones realizadas:**
  - Mensaje del commit: Correccion de sentencias faltantates.
  - Se modificaron 2 archivo(s).
- **Archivos afectados:**
  - `Documentos/DOCUMENTACION_SISTEMA_EVA2026I.md` (M)
  - `reportes/reporte_profesor_carrera_cc.php` (M)

#### 5) Commit `f51c72b` - Actualizacion de la base de datos
- **Autor:** Tu Nombre
- **Fecha:** 2026-05-19 10:15:41 -0600
- **Acciones realizadas:**
  - Mensaje del commit: Actualizacion de la base de datos.
  - Se modificaron 2 archivo(s).
- **Archivos afectados:**
  - `Documentos/DOCUMENTACION_SISTEMA_EVA2026I.md` (M)
  - `Documentos/eva2027i.sql` (M)

#### 6) Commit `c4ad184` - Se actualizan las sentencias en el archivo de BD
- **Autor:** Tu Nombre
- **Fecha:** 2026-05-13 13:11:19 -0600
- **Acciones realizadas:**
  - Mensaje del commit: Se actualizan las sentencias en el archivo de BD.
  - Se modificaron 2 archivo(s).
- **Archivos afectados:**
  - `Documentos/DOCUMENTACION_SISTEMA_EVA2026I.md` (M)
  - `Documentos/preparatoria/INFORMACION PREPARATORIA.xlsx` (M)

#### 7) Commit `f1c49fa` - Se agregan los periodos de evaluacion para preparatoria
- **Autor:** Tu Nombre
- **Fecha:** 2026-05-13 13:11:06 -0600
- **Acciones realizadas:**
  - Mensaje del commit: Se agregan los periodos de evaluacion para preparatoria.
  - Se modificaron 2 archivo(s).
- **Archivos afectados:**
  - `Documentos/DOCUMENTACION_SISTEMA_EVA2026I.md` (M)
  - `lib/config.php` (M)

#### 8) Commit `ae59dee` - Informacion para BD de evaluacion de preparatoria
- **Autor:** Tu Nombre
- **Fecha:** 2026-05-13 13:10:35 -0600
- **Acciones realizadas:**
  - Mensaje del commit: Informacion para BD de evaluacion de preparatoria.
  - Se agregaron 1 archivo(s).
  - Se modificaron 1 archivo(s).
- **Archivos afectados:**
  - `Documentos/DOCUMENTACION_SISTEMA_EVA2026I.md` (M)
  - `Documentos/info_prepa.sql` (A)

**Leyenda:** `A` = agregado, `M` = modificado, `D` = eliminado, `R` = renombrado, `C` = copiado.

<!-- AUTO_COMMIT_HISTORY_END -->































