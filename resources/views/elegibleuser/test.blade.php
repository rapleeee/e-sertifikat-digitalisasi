<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Pencarian Eligible</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 text-slate-800 p-10">
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">Test Pencarian Eligible</h1>
        
        <div class="space-y-4">
            <input 
                id="nisInput" 
                type="text" 
                placeholder="Masukkan NIS (contoh: 232410001)" 
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
            
            <button 
                id="searchBtn" 
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded-lg"
            >
                Cari
            </button>
        </div>
        
        <!-- Loading Indicator - ALWAYS VISIBLE FOR TEST -->
        <div id="loadingIndicator" class="mt-6 p-4 bg-blue-100 border border-blue-300 rounded-lg text-blue-800 text-center">
            <div class="flex justify-center items-center gap-3 mb-2">
                <span class="inline-block h-6 w-6 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></span>
                <span class="font-semibold">Sedang mencari siswa...</span>
            </div>
            <p class="text-xs mt-2">Status: Menunggu pencarian</p>
        </div>
        
        <!-- Result Container -->
        <div id="resultContainer" class="mt-6"></div>
        
        <!-- Debug Console -->
        <div id="debugConsole" class="mt-6 p-4 bg-slate-200 rounded-lg text-xs font-mono max-h-48 overflow-y-auto">
            <div class="font-bold mb-2">Debug Console:</div>
            <div id="debugLog"></div>
        </div>
    </div>

    <script>
        const nisInput = document.getElementById('nisInput');
        const searchBtn = document.getElementById('searchBtn');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const resultContainer = document.getElementById('resultContainer');
        const debugLog = document.getElementById('debugLog');
        
        function log(message) {
            console.log(message);
            const timestamp = new Date().toLocaleTimeString();
            debugLog.innerHTML += `<div>[${timestamp}] ${message}</div>`;
            debugLog.parentElement.scrollTop = debugLog.parentElement.scrollHeight;
        }
        
        log('✅ Page loaded');
        log('Elements initialized');
        
        searchBtn.addEventListener('click', async function() {
            const nis = nisInput.value.trim();
            
            if (!nis) {
                log('❌ NIS kosong');
                alert('Masukkan NIS terlebih dahulu');
                return;
            }
            
            log(`🔍 Searching for NIS: ${nis}`);
            loadingIndicator.classList.remove('hidden');
            resultContainer.innerHTML = '';
            
            try {
                log('📡 Fetching from /api/pencarian-eligible...');
                const response = await fetch(`/api/pencarian-eligible?type=nis&query=${nis}`);
                log(`📥 Response received: ${response.status}`);
                
                const data = await response.json();
                log(`✅ JSON parsed: ${JSON.stringify(data)}`);
                
                // Show loading for 2 seconds
                await new Promise(resolve => setTimeout(resolve, 2000));
                log('⏱️ 2 second delay completed');
                
                loadingIndicator.classList.add('hidden');
                
                if (data.results && data.results.length > 0) {
                    const siswa = data.results[0];
                    log(`✅ Siswa ditemukan: ${siswa.nama}`);
                    
                    const statusColor = siswa.eligibilitas === 'eligible' ? 'green' : 'red';
                    const statusText = siswa.eligibilitas === 'eligible' ? 'ELIGIBLE' : 'TIDAK ELIGIBLE';
                    
                    resultContainer.innerHTML = `
                        <div class="p-6 bg-white border border-slate-200 rounded-xl shadow-lg">
                            <h2 class="text-xl font-bold mb-4 text-${statusColor}-600">${statusText}</h2>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-semibold">Nama:</span> ${siswa.nama}</p>
                                <p><span class="font-semibold">NIS:</span> ${siswa.nis}</p>
                                <p><span class="font-semibold">Kelas:</span> ${siswa.kelas}</p>
                                <p><span class="font-semibold">Jurusan:</span> ${siswa.jurusan || '-'}</p>
                            </div>
                        </div>
                    `;
                } else {
                    log('❌ Siswa tidak ditemukan');
                    resultContainer.innerHTML = `
                        <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                            NIS tidak ditemukan
                        </div>
                    `;
                }
            } catch (error) {
                log(`❌ Error: ${error.message}`);
                loadingIndicator.classList.add('hidden');
                resultContainer.innerHTML = `
                    <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                        Error: ${error.message}
                    </div>
                `;
            }
        });
        
        // Allow Enter key
        nisInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') searchBtn.click();
        });
        
        log('✅ Event listeners attached');
    </script>
</body>
</html>
