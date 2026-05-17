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
            <p class="text-slate-500">Convierte PDF a Imágenes, Imágenes a PDF o quítale el fondo a tus fotos usando IA.</p>
        </button>
    </div>
</div>