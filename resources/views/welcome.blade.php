<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaFactory - Herramientas Multimedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-50 min-h-screen flex flex-col font-sans text-slate-800">

    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm py-4 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-black tracking-tight text-slate-800 cursor-pointer" onclick="switchView('view-home')">
                Media<span class="text-blue-600">Factory</span>
            </h1>
            <nav>
                <button onclick="switchView('view-home')" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition">
                    Inicio
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8 flex flex-col items-center">
        
        <!-- ============================== -->
        <!-- VISTA 1: INICIO (DASHBOARD)    -->
        <!-- ============================== -->
        <div id="view-home" class="w-full max-w-5xl animate-fade-in">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-slate-900 mb-4">¿Qué necesitas hacer hoy?</h2>
                <p class="text-lg text-slate-500">Selecciona una de nuestras herramientas gratuitas</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tarjeta: Descargar Videos -->
                <button onclick="switchView('view-video')" class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-blue-200 transition-all text-left flex flex-col items-start h-full">
                    <div class="bg-blue-100 p-4 rounded-xl text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Descargar Videos</h3>
                    <p class="text-slate-500">Descarga videos de TikTok e Instagram sin marca de agua o extrae el audio en MP3.</p>
                </button>

                <!-- Tarjeta: Convertir Archivos -->
                <button onclick="switchView('view-file')" class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-purple-200 transition-all text-left flex flex-col items-start h-full">
                    <div class="bg-purple-100 p-4 rounded-xl text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Convertir Documentos</h3>
                    <p class="text-slate-500">Convierte archivos PDF a Imágenes JPG (comprimidas en ZIP) o une Imágenes en un solo PDF.</p>
                </button>
            </div>
        </div>

        <!-- ============================== -->
        <!-- VISTA 2: DESCARGADOR DE VIDEOS -->
        <!-- ============================== -->
        <div id="view-video" class="w-full max-w-2xl hidden animate-fade-in">
            <button onclick="switchView('view-home')" class="mb-6 flex items-center text-slate-500 hover:text-blue-600 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Inicio
            </button>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
                <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Descargador de Videos</h2>
                <p class="text-slate-500 mb-8">Pega el enlace de TikTok o Instagram abajo</p>

                <!-- Formulario URL -->
                <div class="flex flex-col gap-4">
                    <input type="url" id="mediaUrl" placeholder="https://vt.tiktok.com/..." class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition text-lg">
                    
                    <div class="flex gap-4">
                        <select id="format" class="w-1/3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-4 outline-none">
                            <option value="mp4">Video (MP4)</option>
                            <option value="mp3">Audio (MP3)</option>
                        </select>
                        <button onclick="fetchMetadata()" id="btnInfo" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-lg transition shadow-lg shadow-blue-600/20">
                            Analizar Enlace
                        </button>
                    </div>
                    <div id="errorMsg" class="text-red-500 text-sm hidden text-center mt-2"></div>
                </div>

                <!-- Info y Descarga -->
                <div id="metadataContainer" class="mt-8 hidden bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <div class="flex items-center gap-4">
                        <img id="metaThumb" src="" alt="Thumbnail" class="w-24 h-24 object-cover rounded-lg shadow-sm">
                        <div class="flex-1 overflow-hidden">
                            <h3 id="metaTitle" class="font-bold text-slate-800 truncate text-lg"></h3>
                            <p id="metaDuration" class="text-slate-500 text-sm mt-1"></p>
                        </div>
                    </div>
                    <button onclick="startDownload()" id="btnDownload" class="w-full mt-4 bg-green-500 hover:bg-green-600 text-white py-4 rounded-xl font-bold text-lg transition shadow-lg shadow-green-500/20">
                        Comenzar Descarga
                    </button>
                </div>

                <!-- Progress & Success Video -->
                <div id="progressContainer" class="mt-8 hidden text-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p id="progressStatus" class="text-blue-600 font-medium">Procesando archivo...</p>
                </div>
                <div id="successContainer" class="mt-8 hidden bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                    <h3 class="text-xl font-bold text-green-800 mb-4">¡Archivo Listo!</h3>
                    <a id="downloadLink" href="#" download class="inline-block bg-green-600 text-white font-bold py-3 px-8 rounded-lg shadow-md">Descargar Ahora</a>
                </div>
            </div>
        </div>

        <!-- ============================== -->
        <!-- VISTA 3: CONVERSOR DE ARCHIVOS -->
        <!-- ============================== -->
        <div id="view-file" class="w-full max-w-2xl hidden animate-fade-in">
            <button onclick="switchView('view-home')" class="mb-6 flex items-center text-slate-500 hover:text-purple-600 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Inicio
            </button>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
                <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Conversor de Documentos</h2>
                <p class="text-slate-500 mb-8">Sube tu archivo para transformarlo</p>

                <!-- Formulario Archivo -->
                <div class="flex flex-col gap-4">
                    <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 transition cursor-pointer" onclick="document.getElementById('fileInput').click()">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        <p class="text-slate-600 font-medium">Haz clic para seleccionar tu archivo</p>
                        <p id="fileNameDisplay" class="text-sm text-purple-600 mt-2 font-bold"></p>
                        <input type="file" id="fileInput" class="hidden" onchange="document.getElementById('fileNameDisplay').innerText = this.files[0].name">
                    </div>
                    
                    <div class="flex gap-4">
                        <select id="conversionType" class="w-1/2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-4 outline-none">
                            <option value="pdf_to_img">De PDF a Imágenes (ZIP)</option>
                            <option value="img_to_pdf">De Imagen a PDF</option>
                        </select>
                        <button onclick="uploadFile()" id="btnConvertFile" class="w-1/2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-lg transition shadow-lg shadow-purple-600/20">
                            Convertir
                        </button>
                    </div>
                </div>

                <!-- Progress & Success File -->
                <div id="fileProgress" class="mt-8 hidden text-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-purple-600 mx-auto mb-4"></div>
                    <p id="fileProgressStatus" class="text-purple-600 font-medium">Subiendo y convirtiendo...</p>
                </div>
                <div id="fileResult" class="mt-8 hidden bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                    <h3 class="text-xl font-bold text-green-800 mb-4">¡Conversión Exitosa!</h3>
                    <a id="fileDownloadLink" href="#" download class="inline-block bg-green-600 text-white font-bold py-3 px-8 rounded-lg shadow-md">Descargar Archivo</a>
                </div>
            </div>
        </div>

    </main>

    <!-- Script de Control de Vistas y Lógica -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // --- CONTROLADOR DE VISTAS (SPA Logic) ---
        function switchView(viewId) {
            // Ocultar todas las vistas
            document.getElementById('view-home').classList.add('hidden');
            document.getElementById('view-video').classList.add('hidden');
            document.getElementById('view-file').classList.add('hidden');
            
            // Mostrar la vista solicitada
            document.getElementById(viewId).classList.remove('hidden');

            // Resetear estados visuales por si acaso
            if(viewId === 'view-home') {
                document.getElementById('metadataContainer').classList.add('hidden');
                document.getElementById('progressContainer').classList.add('hidden');
                document.getElementById('successContainer').classList.add('hidden');
                
                document.getElementById('fileProgress').classList.add('hidden');
                document.getElementById('fileResult').classList.add('hidden');
            }
        }

        // --- LÓGICA DE VIDEOS (La que ya tenías funcionando) ---
        async function fetchMetadata() {
            const url = document.getElementById('mediaUrl').value;
            if(!url) return;
            
            document.getElementById('errorMsg').classList.add('hidden');
            document.getElementById('btnInfo').innerText = 'Analizando...';

            try {
                const response = await fetch('/api/fetch-info', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ url })
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Error en el enlace');

                document.getElementById('metaTitle').innerText = data.title;
                document.getElementById('metaThumb').src = data.thumbnail;
                document.getElementById('metadataContainer').classList.remove('hidden');
            } catch (error) {
                document.getElementById('errorMsg').innerText = error.message;
                document.getElementById('errorMsg').classList.remove('hidden');
            } finally {
                document.getElementById('btnInfo').innerText = 'Analizar Enlace';
            }
        }

        async function startDownload() {
            const url = document.getElementById('mediaUrl').value;
            const format = document.getElementById('format').value;
            // NUEVO: Atrapamos el título que está en la pantalla
            const title = document.getElementById('metaTitle').innerText || 'video'; 

            document.getElementById('metadataContainer').classList.add('hidden');
            document.getElementById('progressContainer').classList.remove('hidden');

            try {
                const response = await fetch('/api/download/start', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    // NUEVO: Agregamos el título a los datos que enviamos al backend
                    body: JSON.stringify({ url, format, title }) 
                });
                const data = await response.json();
                if (data.job_id) pollStatus(data.job_id, 'video');
            } catch (error) {
                alert('Error al iniciar descarga.');
            }
        }

        // --- LÓGICA DE ARCHIVOS ---
        async function uploadFile() {
            const fileInput = document.getElementById('fileInput');
            const type = document.getElementById('conversionType').value;
            
            if (fileInput.files.length === 0) {
                alert("Selecciona un archivo primero.");
                return;
            }

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('type', type);

            document.getElementById('fileProgress').classList.remove('hidden');
            document.getElementById('fileResult').classList.add('hidden');
            document.getElementById('btnConvertFile').disabled = true;

            try {
                const response = await fetch('/api/file/convert', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await response.json();
                if (data.job_id) {
                    pollStatus(data.job_id, 'file');
                } else {
                    throw new Error(data.error);
                }
            } catch (error) {
                document.getElementById('fileProgress').classList.add('hidden');
                alert("Error: " + error.message);
                document.getElementById('btnConvertFile').disabled = false;
            }
        }

        // --- POLLING UNIFICADO (Sirve para videos y archivos) ---
        function pollStatus(jobId, moduleType) {
            const interval = setInterval(async () => {
                // Endpoint dependiendo si es video o archivo
                const endpoint = moduleType === 'video' ? `/api/download/status/${jobId}` : `/api/file/status/${jobId}`;
                
                try {
                    const response = await fetch(endpoint);
                    const data = await response.json();

                    if (data.status === 'completed') {
                        clearInterval(interval);
                        if(moduleType === 'video') {
                            document.getElementById('progressContainer').classList.add('hidden');
                            document.getElementById('downloadLink').href = data.download_url;
                            document.getElementById('successContainer').classList.remove('hidden');
                        } else {
                            document.getElementById('fileProgress').classList.add('hidden');
                            document.getElementById('fileDownloadLink').href = data.download_url;
                            document.getElementById('fileResult').classList.remove('hidden');
                            document.getElementById('btnConvertFile').disabled = false;
                        }
                    } else if (data.status === 'failed') {
                        clearInterval(interval);
                        alert('El proceso falló en el servidor.');
                        if(moduleType === 'file') document.getElementById('btnConvertFile').disabled = false;
                    }
                } catch (error) {
                    console.error('Error haciendo polling:', error);
                }
            }, 3000);
        }
    </script>
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</body>
</html>