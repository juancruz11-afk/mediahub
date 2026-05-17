<div id="view-file" class="w-full max-w-2xl hidden animate-fade-in">
    <button onclick="switchView('view-home')" class="mb-6 flex items-center text-slate-500 hover:text-purple-600 transition font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al Inicio
    </button>

    <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
        <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Suite de Herramientas</h2>
        <p class="text-slate-500 mb-8">Sube tu archivo o imagen para transformarlo</p>

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
                    <option value="remove_bg">💥 Quitar Fondo a Imagen (PNG)</option>
                </select>
                <button onclick="uploadFile()" id="btnConvertFile" class="w-1/2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-lg transition shadow-lg shadow-purple-600/20">
                    Procesar
                </button>
            </div>
        </div>

        <div id="fileProgress" class="mt-8 hidden text-center">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-purple-600 mx-auto mb-4"></div>
            <p id="fileProgressStatus" class="text-purple-600 font-medium">Subiendo y procesando con IA...</p>
        </div>
        <div id="fileResult" class="mt-8 hidden bg-green-50 border border-green-200 rounded-xl p-6 text-center">
            <h3 class="text-xl font-bold text-green-800 mb-4">¡Proceso Exitoso!</h3>
            <a id="fileDownloadLink" href="#" download class="inline-block bg-green-600 text-white font-bold py-3 px-8 rounded-lg shadow-md">Descargar Archivo</a>
        </div>
    </div>
</div>