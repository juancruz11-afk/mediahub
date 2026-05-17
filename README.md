# 🛠️ MediaFactory - Herramientas Multimedia Asíncronas

¡Bienvenido a **MediaFactory**! Una potente aplicación web moderna tipo "Navaja Suiza" construida con **Laravel 11** y **Tailwind CSS**. Este sistema permite procesar descargas de redes sociales y realizar conversiones de documentos de manera 100% asíncrona mediante colas de trabajo (*Queue Jobs*), evitando la sobrecarga del servidor y garantizando una experiencia de usuario fluida (estilo SPA).

---

## 🚀 Características Principales

### 📺 Módulo de Descarga de Videos
*   **Compatibilidad:** Descarga contenido de **TikTok** (incluyendo enlaces móviles `vt.` y `vm.`) e **Instagram** mediante URL.
*   **Sin Marca de Agua:** Extracción limpia utilizando los extractores nativos de `yt-dlp`.
*   **Formatos:** Descarga de video en **MP4** o extracción directa de audio en **MP3**.
*   **Nombres Limpios:** Los archivos se renombran automáticamente usando el título del video sanitizado (`Str::slug()`) para mayor comodidad del usuario.

### 📄 Módulo de Conversión de Documentos
*   **PDF a Imágenes:** Extrae todas las páginas de un archivo PDF y las empaqueta automáticamente en un archivo **ZIP** con imágenes JPG de alta calidad.
*   **Imágenes a PDF (Próximamente expandido):** Une imágenes en un único documento PDF optimizado.
*   **Rutas Seguras:** Normalización y limpieza estricta de rutas binarias para entornos Windows y Linux.

### 🛡️ Arquitectura y Seguridad
*   **Procesamiento en Segundo Plano:** El trabajo pesado (`yt-dlp`, `pdftoppm`, `magick`) se delega a la base de datos mediante *Queue Workers*.
*   **Seguridad CLI:** Ejecución de binarios mediante el componente seguro `Process` de Symfony, mitigando riesgos de inyección arbitraria de comandos de shell.
*   **Interfaz Limpia:** Panel de control responsivo con Tailwind CSS estructurado como una Single Page Application mediante JavaScript nativo.

---

## 📐 Arquitectura del Sistema

El proyecto sigue una estructura limpia separando la lógica de controladores de la capa de ejecución de comandos mediante **Servicios** y **Jobs**:

```text
app/
├── Http/Controllers/
│   ├── MediaController.php      # Validación de URLs y despacho de descargas
│   └── FileController.php       # Gestión de uploads de archivos y despacho de conversiones
├── Jobs/
│   ├── ProcessMediaDownload.php # Worker encargado de correr yt-dlp/ffmpeg
│   └── ProcessFile.php          # Worker encargado de correr poppler/imagemagick
└── Services/
    ├── MediaExtractorService.php # Envoltorio seguro para CLI de video
    └── FileConverterService.php  # Envoltorio seguro para herramientas de oficina
