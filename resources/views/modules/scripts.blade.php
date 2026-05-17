<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    function switchView(viewId) {
        document.getElementById('view-home').classList.add('hidden');
        document.getElementById('view-video').classList.add('hidden');
        document.getElementById('view-file').classList.add('hidden');
        
        document.getElementById(viewId).classList.remove('hidden');

        if(viewId === 'view-home') {
            document.getElementById('metadataContainer').classList.add('hidden');
            document.getElementById('progressContainer').classList.add('hidden');
            document.getElementById('successContainer').classList.add('hidden');
            
            document.getElementById('fileProgress').classList.add('hidden');
            document.getElementById('fileResult').classList.add('hidden');
        }
    }

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
        const title = document.getElementById('metaTitle').innerText || 'video';

        document.getElementById('metadataContainer').classList.add('hidden');
        document.getElementById('progressContainer').classList.remove('hidden');

        try {
            const response = await fetch('/api/download/start', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ url, format, title })
            });
            const data = await response.json();
            if (data.job_id) pollStatus(data.job_id, 'video');
        } catch (error) {
            alert('Error al iniciar descarga.');
        }
    }

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

            // NUEVO: Atrapamos el error exacto que nos manda Laravel
            if (!response.ok) {
                if (data.errors) {
                    // Saca el primer error de la lista de validación
                    throw new Error(Object.values(data.errors)[0][0]); 
                }
                throw new Error(data.error || 'Error desconocido');
            }

            if (data.job_id) {
                pollStatus(data.job_id, 'file');
            }
        } catch (error) {
            document.getElementById('fileProgress').classList.add('hidden');
            alert("Error de validación: " + error.message);
            document.getElementById('btnConvertFile').disabled = false;
        }
    }

    function pollStatus(jobId, moduleType) {
        const interval = setInterval(async () => {
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