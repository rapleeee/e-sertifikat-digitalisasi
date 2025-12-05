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
        <h2 class="text-2xl font-semibold text-gray-900">Upload Foto Sertifikat</h2>
        <p class="text-sm text-gray-500 mt-1">
          Unggah satu atau beberapa foto sertifikat. Sistem akan mencocokkan setiap file dengan siswa berdasarkan NIS pada nama file.
        </p>
      </header>

      <div class="w-full bg-white shadow-sm rounded-2xl p-8 border border-gray-200">
        <div class="mb-6 space-y-2 text-xs text-gray-600">
          <p class="font-semibold text-slate-700 text-sm">Cara kerja fitur ini:</p>
          <p>1. Setiap file mewakili <span class="font-semibold">1 sertifikat untuk 1 siswa</span>.</p>
          <p>2. Nama file <span class="font-semibold">harus sama persis dengan NIS</span> siswa, tanpa spasi atau teks tambahan.</p>
          <p>3. Sistem membaca NIS dari nama file, mencari siswa dengan NIS tersebut, lalu otomatis membuat sertifikat baru dengan data di bawah.</p>
          <p>4. Satu siswa boleh memiliki banyak sertifikat; upload beberapa file dengan NIS yang sama tidak masalah.</p>
          <p class="mt-1">Contoh nama file yang benar: <span class="font-semibold">123456789.jpg</span> atau <span class="font-semibold">123456789.png</span>.</p>
        </div>

        <form action="{{ route('sertifikat.upload.massal') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf

          <div class="w-full space-y-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Upload Foto Sertifikat
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
                        accept="image/*"
                        x-ref="fileInput"
                        x-on:change="handleBrowse">
                  </label>
                  <span>atau drag & drop</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                  Format JPG/PNG, maksimum 2MB per file. File yang melebihi batas akan ditolak otomatis.
                </p>
              </div>
            </div>

            <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4" x-show="previewUrls.length">
              <template x-for="(preview, index) in previewUrls" :key="preview.id">
                <div class="relative group">
                  <img :src="preview.src"
                       class="h-24 w-24 object-cover rounded-lg border border-gray-200 shadow-sm cursor-zoom-in"
                       x-on:click="previewImage(index)"
                       :alt="files[index]?.name ?? 'Preview Sertifikat'">
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
            <p>• Nama file harus sama dengan NIS siswa tanpa spasi (<span class="font-semibold">123456789.png</span>).</p>
            <p>• File dengan NIS yang tidak ditemukan akan dilewati dan ditampilkan dalam pesan setelah proses upload.</p>
            <p>• Semua file yang diunggah akan menggunakan <span class="font-semibold">jenis, judul, dan tanggal</span> yang kamu isi di atas.</p>
            <p>• Untuk menambah sertifikat berbeda per siswa (judul/jenis berbeda), gunakan form <span class="font-semibold">Tambah Sertifikat</span> per siswa.</p>
          </div>

          <div class="mt-4 flex justify-end">
            <button
                type="submit"
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
        maxSize: 2 * 1024 * 1024,
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

          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

          newFiles.forEach(file => {
            if (!allowedTypes.includes(file.type)) {
              Swal.fire({
                title: 'Format Tidak Didukung',
                text: `${file.name} bukan file JPG atau PNG.`,
                icon: 'error',
                confirmButtonText: 'Mengerti',
                customClass: { popup: 'rounded-2xl' }
              });
              return;
            }

            if (file.size > this.maxSize) {
              Swal.fire({
                title: 'Ukuran File Terlalu Besar',
                text: `${file.name} melebihi batas 2MB dan tidak diunggah.`,
                icon: 'error',
                confirmButtonText: 'Mengerti',
                customClass: { popup: 'rounded-2xl' }
              });
            } else {
              accepted.push(file);
            }
          });

          if (!accepted.length) {
            return;
          }

          this.files = [...this.files, ...accepted];
          this.buildPreviews();
          this.syncInput();
        },
        canSubmit() {
          return this.files.length > 0 && this.jenis && this.judul && this.tanggal;
        },
        buildPreviews() {
          this.previewUrls.forEach(preview => {
            if (preview?.src) {
              URL.revokeObjectURL(preview.src);
            }
          });

          this.previewUrls = this.files.map((file, index) => ({
            id: `${file.name}-${index}-${Date.now()}`,
            src: URL.createObjectURL(file)
          }));
        },
        removeFile(index) {
          if (index < 0 || index >= this.files.length) {
            return;
          }

          this.files.splice(index, 1);
          this.buildPreviews();
          this.syncInput();
        },
        previewImage(index) {
          const preview = this.previewUrls[index];
          const file = this.files[index];

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
            return;
          }

          const dataTransfer = new DataTransfer();
          this.files.forEach(file => dataTransfer.items.add(file));
          input.files = dataTransfer.files;
        }
      };
    }
  </script>
</x-app-layout>
