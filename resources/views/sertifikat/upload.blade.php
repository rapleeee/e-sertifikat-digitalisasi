@php
    $flashData = [
        'success' => session('success'),
        'error' => session('error'),
        'skipped' => session('skipped'),
        'validation' => $errors->all(),
    ];
@endphp

<x-app-layout>
  <div
      x-data="uploadPage()"
      x-init="init()"
      :class="open ? 'ml-64' : ''"
      class="transition-all duration-300">

    <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-10 w-full mx-auto space-y-6">
      <header>
        <h2 class="text-2xl font-semibold text-gray-900">Upload Sertifikat (Foto/PDF)</h2>
        <p class="text-sm text-gray-500 mt-1">
          Unggah satu atau beberapa sertifikat dalam format foto (JPG/PNG) atau PDF. Sistem akan mencocokkan setiap file dengan siswa berdasarkan NIS pada nama file.
        </p>
      </header>

      <div class="w-full bg-white shadow-sm rounded-2xl p-8 border border-gray-200">
        <div class="mb-6 space-y-2 text-xs text-gray-600">
            <p class="font-semibold text-slate-700 text-sm">Cara kerja fitur upload massal:</p>
            <p>1. Setiap file mewakili <span class="font-semibold">1 sertifikat untuk 1 siswa</span>.</p>
            <p>2. Nama file <span class="font-semibold">harus sama persis dengan NIS</span> siswa, tanpa spasi atau teks tambahan.</p>
            <p>3. Sistem membaca NIS dari nama file, mencari siswa dengan NIS tersebut, lalu otomatis membuat sertifikat baru dengan data di bawah.</p>
            <p>4. Jika ada file yang gagal, file lain tetap akan diunggah (partial success).</p>
            <p class="mt-1">Contoh nama file yang benar: <span class="font-semibold">252610002.jpg</span>, <span class="font-semibold">252610005.png</span>, atau <span class="font-semibold">252610011.pdf</span>.</p>
            <p class="mt-2 bg-blue-50 border border-blue-200 rounded-lg p-2">✓ Format: <span class="font-semibold">JPG, PNG, PDF</span> | Ukuran: hingga <span class="font-semibold">10MB</span> per file | Bisa upload banyak file sekaligus tanpa batas total</p>

        <form id="uploadForm" class="space-y-6" enctype="multipart/form-data">
          @csrf

          <div class="w-full space-y-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Upload Sertifikat (Foto/PDF)
            </label>

            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-xl transition-colors duration-200"
                 :class="highlight ? 'border-orange-400 bg-orange-50/40' : 'border-gray-300 hover:border-orange-400'"
                 x-on:dragover.prevent="handleDragEnter"
                 x-on:dragenter.prevent="handleDragEnter"
                 x-on:dragleave.prevent="handleDragLeave"
                 x-on:dragexit.prevent="handleDragLeave"
                 x-on:drop="handleDrop">

              <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600 justify-center items-center gap-2">
                  <label class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                    <span>Pilih berkas</span>
                    <input
                        type="file"
                        name="foto_sertifikat[]"
                        class="sr-only"
                        multiple
                        accept="image/jpeg,image/jpg,image/png,application/pdf"
                        x-ref="fileInput"
                        x-on:change="handleBrowse">
                  </label>
                  <span>atau drag & drop</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                  Format JPG/PNG/PDF, hingga 10MB per file. Bisa upload banyak file sekaligus.
                </p>
              </div>
            </div>

            <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4" x-show="previewUrls.length">
              <template x-for="(preview, index) in previewUrls" :key="preview.id">
                <div class="relative group">
                  <template x-if="files[index]?.type === 'application/pdf'">
                    <div class="h-24 w-24 rounded-lg border border-gray-200 shadow-sm flex items-center justify-center bg-red-50">
                      <div class="text-center">
                        <svg class="w-8 h-8 text-red-500 mx-auto mb-1" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M7 18c-.5 0-.5.5-.5 1s0 1 .5 1h10c.5 0 .5-.5.5-1s0-1-.5-1H7zm0-3h10c.5 0 .5-.5.5-1s0-1-.5-1H7c-.5 0-.5.5-.5 1s0 1 .5 1zm0-5h10c.5 0 .5-.5.5-1s0-1-.5-1H7c-.5 0-.5.5-.5 1s0 1 .5 1zm11-6H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H6V4h12v12z"/>
                        </svg>
                        <p class="text-xs font-semibold text-red-700">PDF</p>
                      </div>
                    </div>
                  </template>
                  <template x-if="files[index]?.type !== 'application/pdf'">
                    <img :src="preview.src"
                         class="h-24 w-24 object-cover rounded-lg border border-gray-200 shadow-sm cursor-zoom-in"
                         x-on:click="previewImage(index)"
                         :alt="files[index]?.name ?? 'Preview Sertifikat'">
                  </template>
                  <button type="button"
                          class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow opacity-0 group-hover:opacity-100 transition"
                          x-on:click="removeFile(index)">
                    <svg class="w-4 h-4" fill="none" stroke="CurrentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                  <p class="mt-1 text-[10px] text-gray-500 truncate" x-text="files[index]?.name"></p>
                </div>
              </template>
            </div>

            <input type="hidden" name="max_size_notice" value="true">
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Jenis Sertifikat</label>
              <select
                  name="jenis_sertifikat"
                  x-model="jenis"
                  required
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                <option value="" disabled selected>Pilih jenis sertifikat</option>
                <option value="BNSP">BNSP</option>
                <option value="Kompetensi">Kompetensi</option>
                <option value="Prestasi">Prestasi</option>
                <option value="Internasional">Internasional</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Tanggal Diraih</label>
              <input
                  type="date"
                  name="tanggal_diraih"
                  x-model="tanggal"
                  max="{{ now()->toDateString() }}"
                  required
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Judul Sertifikat</label>
              <input
                  type="text"
                  name="judul_sertifikat"
                  x-model="judul"
                  required
                  placeholder="Misal: Sertifikat Kompetensi UI/UX Nasional"
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
            </div>
          </div>

          <div class="mt-6 space-y-3 text-xs text-gray-500 leading-relaxed">
            <p>• Nama file <span class="font-semibold">HARUS</span> sama dengan NIS siswa (contoh: <span class="font-semibold">252610002.pdf</span>), tanpa karakter lain.</p>
            <p>• Sistem akan cocokkan file dengan siswa berdasarkan NIS dari nama file.</p>
            <p>• Jika NIS tidak ditemukan di database, file akan dilewati dan ditampilkan dalam laporan.</p>
            <p>• Jika ada yang gagal, file lainnya tetap akan berhasil diunggah - hanya yang error saja yang tidak disimpan.</p>
            <p>• Semua file yang berhasil diunggah akan menggunakan <span class="font-semibold">jenis, judul, dan tanggal</span> yang Anda isi di atas.</p>
            <p>• Satu siswa dapat memiliki banyak sertifikat; upload ulang dengan NIS yang sama tidak masalah.</p>
          </div>

          <div class="mt-4 flex justify-end">
            <button
                type="button"
                @click="submitForm()"
                :disabled="!canSubmit()"
                :class="canSubmit()
                    ? 'px-6 py-2.5 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-150 text-sm'
                    : 'px-6 py-2.5 bg-orange-300 text-white font-medium rounded-lg cursor-not-allowed opacity-70 transition-all duration-150 text-sm'">
              Upload Sertifikat
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>

  <script>
    window.__uploadFlash = @json($flashData);

    function uploadPage() {
      return {
        open: JSON.parse(localStorage.getItem('sidebarOpen') || 'true'),
        highlight: false,
        files: [],
        previewUrls: [],
        maxSize: 100 * 1024 * 1024, // 100MB untuk support file besar dan PDF multi-halaman
        jenis: '',
        judul: '',
        tanggal: '',
        init() {
          window.addEventListener('sidebar-toggled', () => {
            this.open = JSON.parse(localStorage.getItem('sidebarOpen'));
          });

          this.showFlashMessages();
        },
        showFlashMessages() {
          const data = window.__uploadFlash || {};

          if (Array.isArray(data.validation) && data.validation.length) {
            const list = data.validation.map(item => `<li>${item}</li>`).join('');
            Swal.fire({
              title: 'Validasi Gagal',
              html: `<ul class="text-left space-y-1">${list}</ul>`,
              icon: 'error',
              confirmButtonText: 'Mengerti',
              customClass: { popup: 'rounded-2xl' }
            });
          }

          if (data.error) {
            Swal.fire({
              title: 'Gagal',
              text: data.error,
              icon: 'error',
              confirmButtonText: 'OK',
              customClass: { popup: 'rounded-2xl' }
            });
          }

          if (data.success) {
            Swal.fire({
              title: 'Berhasil',
              text: data.success,
              icon: 'success',
              confirmButtonText: 'Sip',
              customClass: { popup: 'rounded-2xl' }
            });
          }

          if (Array.isArray(data.skipped) && data.skipped.length) {
            const list = data.skipped.map(item => `<li>${item}</li>`).join('');
            Swal.fire({
              title: 'Beberapa File Dilewati',
              html: `<p class="mb-3">Periksa kembali NIS berikut:</p><ul class="text-left space-y-1">${list}</ul>`,
              icon: 'warning',
              confirmButtonText: 'Mengerti',
              customClass: { popup: 'rounded-2xl' }
            });
          }
        },
        handleDragEnter() {
          this.highlight = true;
        },
        handleDragLeave() {
          this.highlight = false;
        },
        handleDrop(event) {
          event.preventDefault();
          this.highlight = false;
          const dropped = Array.from(event.dataTransfer?.files || []);
          this.addFiles(dropped);
        },
        handleBrowse(event) {
          const selected = Array.from(event.target.files || []);
          this.addFiles(selected);
          event.target.value = '';
        },
        addFiles(newFiles) {
          if (!newFiles.length) {
            return;
          }

          const accepted = [];
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

          newFiles.forEach(file => {
            if (!allowedTypes.includes(file.type)) {
              Swal.fire({
                title: 'Format Tidak Didukung',
                text: `${file.name} bukan file JPG, PNG, atau PDF.`,
                icon: 'error',
                confirmButtonText: 'Mengerti',
                customClass: { popup: 'rounded-2xl' }
              });
              return;
            }

            if (file.size > this.maxSize) {
              Swal.fire({
                title: 'Ukuran File Terlalu Besar',
                text: `${file.name} melebihi batas 100MB dan tidak diunggah.`,
                icon: 'error',
                confirmButtonText: 'Mengerti',
                customClass: { popup: 'rounded-2xl' }
              });
            } else {
              console.log(`✓ Adding file: ${file.name} (${file.type}, ${(file.size / 1024 / 1024).toFixed(2)}MB)`);
              accepted.push(file);
            }
          });

          if (!accepted.length) {
            console.warn('No files passed validation');
            return;
          }

          // Add accepted files to the files array
          console.log(`Adding ${accepted.length} files to array. Current count: ${this.files.length}`);
          accepted.forEach(file => {
            this.files.push(file);
            console.log(`File added. Total files: ${this.files.length}`);
          });
          
          console.log(`✓ All files added to array. Total: ${this.files.length}`);
          
          // Rebuild previews
          try {
            this.buildPreviews();
            console.log(`✓ Previews built. Preview count: ${this.previewUrls.length}`);
          } catch (e) {
            console.error('Error building previews:', e);
            // Even if preview fails, files are still in the array
          }
        },
        submitForm() {
          // Validation
          if (!this.files.length) {
            Swal.fire({
              title: 'Belum Ada File',
              text: 'Silakan pilih minimal satu file sertifikat.',
              icon: 'warning',
              confirmButtonText: 'OK',
              customClass: { popup: 'rounded-2xl' }
            });
            return;
          }
          if (!this.jenis) {
            Swal.fire({title:'Jenis Belum Dipilih',text:'Silakan pilih jenis sertifikat.',icon:'warning',confirmButtonText:'OK',customClass:{popup:'rounded-2xl'}});
            return;
          }
          if (!this.judul) {
            Swal.fire({title:'Judul Belum Diisi',text:'Silakan isi judul sertifikat.',icon:'warning',confirmButtonText:'OK',customClass:{popup:'rounded-2xl'}});
            return;
          }
          if (!this.tanggal) {
            Swal.fire({title:'Tanggal Belum Dipilih',text:'Silakan pilih tanggal diraih.',icon:'warning',confirmButtonText:'OK',customClass:{popup:'rounded-2xl'}});
            return;
          }

          console.log('Submitting form with', this.files.length, 'files');
          this.submitWithFormData();
        },
        submitWithFormData() {
          // Upload files sequentially (one at a time) instead of all at once
          // This allows us to upload many files without hitting total size limits
          
          console.log('Starting sequential upload of', this.files.length, 'files');
          
          // Show loading message
          Swal.fire({
            title: 'Mengupload...',
            text: 'Harap tunggu, sistem sedang memproses file Anda...',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
              Swal.showLoading();
            },
            customClass: { popup: 'rounded-2xl' }
          });
          
          // Upload files one by one
          this.uploadFileSequentially(0);
        },
        
        uploadFileSequentially(fileIndex) {
          if (fileIndex >= this.files.length) {
            // All files processed - show final results
            console.log('All files processed');
            Swal.close();
            
            // Refresh page to show uploaded files
            window.location.href = '{{ route('dashboard') }}';
            return;
          }
          
          const file = this.files[fileIndex];
          const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
          console.log(`Uploading file ${fileIndex + 1}/${this.files.length}: ${file.name} (${fileSizeMB}MB)`);
          
          // Create FormData with single file
          const formData = new FormData();
          formData.append('foto_sertifikat[]', file);
          formData.append('jenis_sertifikat', this.jenis);
          formData.append('judul_sertifikat', this.judul);
          formData.append('tanggal_diraih', this.tanggal);
          formData.append('_token', document.querySelector('input[name="_token"]').value);
          
          // Create abort controller untuk timeout handling
          const controller = new AbortController();
          const timeoutId = setTimeout(() => {
            controller.abort();
          }, 120000); // 2 minutes timeout per file (120 seconds)
          
          // Upload this file
          fetch('{{ route('sertifikat.upload.massal') }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            },
            signal: controller.signal
          })
          .then(response => {
            clearTimeout(timeoutId);
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
              return response.json().then(data => ({
                status: response.status,
                data: data,
                isJson: true
              }));
            } else {
              return response.text().then(text => ({
                status: response.status,
                data: text,
                isJson: false
              }));
            }
          })
          .then(result => {
            console.log(`File ${fileIndex + 1} response:`, result);
            
            // If not JSON, show error and stop
            if (!result.isJson) {
              console.error('Server returned non-JSON response');
              let errorMsg = 'Error saat upload file ' + (fileIndex + 1);
              
              if (result.data.includes('413')) {
                errorMsg = 'File terlalu besar! Maksimal 10MB per file.';
              } else if (result.data.includes('414')) {
                errorMsg = 'Request terlalu panjang. Coba lagi.';
              } else if (result.data.includes('502') || result.data.includes('503') || result.data.includes('504')) {
                errorMsg = 'Server sedang sibuk atau offline. Coba lagi dalam beberapa menit.';
              }
              
              Swal.fire({
                title: 'Upload Gagal',
                text: errorMsg,
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: { popup: 'rounded-2xl' }
              });
              return;
            }
            
            // Check for validation errors
            if (result.data.error) {
              console.error('Backend error on file', fileIndex + 1);
              Swal.fire({
                title: 'Gagal Upload File ' + (fileIndex + 1),
                text: result.data.error,
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: { popup: 'rounded-2xl' }
              });
              return;
            }
            
            // File succeeded, move to next
            console.log(`File ${fileIndex + 1} succeeded, moving to next...`);
            this.uploadFileSequentially(fileIndex + 1);
          })
          .catch(error => {
            clearTimeout(timeoutId);
            console.error('Fetch error on file', fileIndex + 1, ':', error);
            
            let errorMsg = 'Error saat upload file ' + (fileIndex + 1);
            let isTimeout = false;
            
            // Detect timeout
            if (error.name === 'AbortError') {
              errorMsg = 'Upload timeout untuk file ' + (fileIndex + 1) + '. File terlalu besar atau koneksi lambat.';
              isTimeout = true;
            } else if (error.message.includes('Failed to fetch')) {
              errorMsg = 'Gagal terhubung ke server. Cek internet Anda:';
              errorMsg += '\n- Pastikan internet stabil\n- Coba refresh halaman\n- Coba kembali dalam beberapa menit';
            } else if (error.message.includes('NetworkError')) {
              errorMsg = 'Error jaringan. Koneksi terputus saat upload.';
            }
            
            Swal.fire({
              title: 'Error Upload' + (isTimeout ? ' (Timeout)' : ''),
              text: errorMsg,
              icon: 'error',
              confirmButtonText: 'OK',
              customClass: { popup: 'rounded-2xl' }
            });
          });
        },
        canSubmit() {
          return this.files.length > 0 && this.jenis && this.judul && this.tanggal;
        },
        buildPreviews() {
          try {
            this.previewUrls.forEach(preview => {
              if (preview?.src) {
                URL.revokeObjectURL(preview.src);
              }
            });

            this.previewUrls = this.files.map((file, index) => {
              let src = null;
              
              // Only create object URLs for non-PDF files
              if (file.type !== 'application/pdf') {
                try {
                  src = URL.createObjectURL(file);
                } catch (e) {
                  console.warn(`Could not create preview for ${file.name}:`, e);
                }
              }
              
              return {
                id: `${file.name}-${index}-${Date.now()}`,
                src: src
              };
            });

            console.log(`✓ Built ${this.previewUrls.length} preview(s)`);
          } catch (e) {
            console.error('Error in buildPreviews:', e);
            // Even if this fails, we don't want to lose the files
            this.previewUrls = this.files.map((file, index) => ({
              id: `${file.name}-${index}-${Date.now()}`,
              src: null
            }));
          }
        },
        removeFile(index) {
          if (index < 0 || index >= this.files.length) {
            return;
          }

          const removed = this.files.splice(index, 1);
          console.log(`✗ Removed file: ${removed[0]?.name}. Remaining: ${this.files.length}`);
          
          this.buildPreviews();
          
          // Only sync if there are files left
          if (this.files.length > 0) {
            this.syncInput();
          } else {
            // Clear the input if no files left
            const input = this.$refs.fileInput;
            if (input) {
              input.value = '';
              const dataTransfer = new DataTransfer();
              input.files = dataTransfer.files;
              console.log('✓ Cleared file input (no files remaining)');
            }
          }
        },
        previewImage(index) {
          const preview = this.previewUrls[index];
          const file = this.files[index];

          // Skip preview for PDF files
          if (file?.type === 'application/pdf') {
            return;
          }

          if (!preview) {
            return;
          }

          Swal.fire({
            title: file?.name ?? 'Preview Sertifikat',
            imageUrl: preview.src,
            imageAlt: file?.name ?? 'Preview Sertifikat',
            showConfirmButton: true,
            confirmButtonText: 'Tutup',
            customClass: { popup: 'rounded-2xl' }
          });
        },
        syncInput() {
          const input = this.$refs.fileInput;
          if (!input) {
            console.error('File input element not found');
            Swal.fire({
              title: 'Kesalahan',
              text: 'File input tidak ditemukan. Silakan refresh halaman.',
              icon: 'error',
              confirmButtonText: 'OK',
              customClass: { popup: 'rounded-2xl' }
            });
            return false;
          }

          if (this.files.length === 0) {
            console.warn('No files in files array to sync');
            // Clear the input
            input.value = '';
            const dataTransfer = new DataTransfer();
            input.files = dataTransfer.files;
            return false;
          }

          try {
            console.log(`Starting sync of ${this.files.length} file(s)...`);
            
            // Create a new DataTransfer object
            const dataTransfer = new DataTransfer();
            
            // Add each file to the DataTransfer
            for (let i = 0; i < this.files.length; i++) {
              const file = this.files[i];
              console.log(`[${i}] Adding: ${file.name} (${file.type}, ${(file.size / 1024 / 1024).toFixed(2)}MB)`);
              try {
                dataTransfer.items.add(file);
              } catch (addError) {
                console.error(`Failed to add file ${i} (${file.name}):`, addError);
                throw new Error(`Gagal menambahkan file: ${file.name}`);
              }
            }
            
            // Update the input's files
            input.files = dataTransfer.files;
            
            console.log(`✓ Successfully synced ${input.files.length} file(s) to input`);
            console.log('Synced files:', Array.from(input.files).map(f => `${f.name} (${f.type})`));
            
            // Verify the sync worked
            if (input.files.length !== this.files.length) {
              console.error('FILE SYNC MISMATCH!', {
                'this.files.length': this.files.length,
                'input.files.length': input.files.length
              });
              Swal.fire({
                title: 'Kesalahan Sync',
                text: `Hanya ${input.files.length} dari ${this.files.length} file yang ter-sync. Silakan coba lagi.`,
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: { popup: 'rounded-2xl' }
              });
              return false;
            }
            
            return true;
          } catch (e) {
            console.error('✗ Critical error syncing files:', e.message);
            Swal.fire({
              title: 'Kesalahan Upload',
              text: 'Terjadi kesalahan saat menyiapkan file: ' + e.message,
              icon: 'error',
              confirmButtonText: 'OK',
              customClass: { popup: 'rounded-2xl' }
            });
            return false;
          }
        }
      };
    }
  </script>
</x-app-layout>
