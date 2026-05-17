<!-- resources/views/welcome.blade.php -->
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-lg border border-gray-100">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Descargador Multimedia</h1>
    
    <!-- Espacio para Ads (AdSense) -->
    <div class="w-full h-24 bg-gray-100 flex items-center justify-center mb-6 text-gray-400 text-sm">
        [Espacio Publicitario Monetización]
    </div>

    <form id="downloadForm" class="flex flex-col gap-4">
        <input type="url" id="mediaUrl" placeholder="Pega el enlace de TikTok o Instagram..." 
               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
        
        <div class="flex gap-4">
            <button type="button" onclick="fetchMetadata()" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
                Obtener Info
            </button>
            <select id="format" class="w-1/2 rounded-lg border border-gray-300 px-4 py-3">
                <option value="mp4">Video (MP4)</option>
                <option value="mp3">Audio (MP3)</option>
            </select>
        </div>
    </form>

    <!-- AJAX Progress Container -->
    <div id="resultContainer" class="mt-8 hidden">
        <div id="metadataDisplay" class="flex items-center gap-4 mb-4"></div>
        <button onclick="startDownload()" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition">
            Iniciar Descarga
        </button>
        <div id="progressStatus" class="mt-4 text-center text-sm font-medium text-gray-600"></div>
    </div>
</div>

<script>
    let currentUrl = '';
    
    async function fetchMetadata() {
        const url = document.getElementById('mediaUrl').value;
        currentUrl = url;
        // Petición AJAX (Fetch API) a MediaController@fetchInfo...
    }

    async function startDownload() {
        const format = document.getElementById('format').value;
        const response = await fetch('/api/download/start', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({url: currentUrl, format: format})
        });
        
        const data = await response.json();
        pollStatus(data.job_id);
    }

    function pollStatus(jobId) {
        document.getElementById('progressStatus').innerText = 'Procesando en el servidor...';
        const interval = setInterval(async () => {
            const res = await fetch(`/api/download/status/${jobId}`);
            const statusData = await res.json();
            
            if (statusData.status === 'completed') {
                clearInterval(interval);
                document.getElementById('progressStatus').innerHTML = `<a href="${statusData.download_url}" class="text-blue-600 underline" download>Haz clic aquí para descargar tu archivo</a>`;
            } else if (statusData.status === 'failed') {
                clearInterval(interval);
                document.getElementById('progressStatus').innerText = 'Error en la descarga. Verifica el enlace.';
            }
        }, 3000); // Poll cada 3 segundos
    }
</script>