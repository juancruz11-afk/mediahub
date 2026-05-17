<div id="view-video" class="w-full max-w-2xl hidden animate-fade-in">
    <button onclick="switchView('view-home')" class="mb-6 flex items-center text-slate-500 hover:text-blue-600 transition font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al Inicio
    </button>

    <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
        <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Descargador de Videos</h2>
        <p class="text-slate-500 mb-8">Pega el enlace de TikTok o Instagram abajo</p>

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