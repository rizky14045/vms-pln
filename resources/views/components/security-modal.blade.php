<div>
    <!-- Modal Overlay -->
    <div id="securityModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <!-- Modal Card -->
      <div class="w-[min(640px,92vw)] bg-white rounded-2xl shadow-xl p-6 md:p-8 relative">
        <!-- Close -->
        <button id="closeBtn" aria-label="Tutup" class="absolute top-4 right-4 text-slate-600 hover:text-slate-800">✕</button>

        <div class="flex items-start gap-4">
          <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-extrabold">✓</div>
          <div>
            <h3 id="modalTitle" class="text-lg font-semibold text-slate-900">Kami menjamin keamanan data Anda</h3>
            <p class="text-slate-500 mt-1">Perlindungan penuh untuk data sensitif</p>
          </div>
        </div>

        <div id="modalDesc" class="mt-4 text-slate-700 leading-relaxed">
          <p>Kami menjaga privasi dan keamanan semua data pribadi Anda, termasuk:</p>
          <ul class="list-disc list-inside mt-2 text-slate-700">
            <li>Data wajah (biometrik)</li>
            <li>Email dan nomor handphone</li>
            <li>Informasi akun dan data lainnya</li>
          </ul>
          <p class="mt-3">Data hanya digunakan untuk tujuan yang Anda setujui, disimpan aman dengan enkripsi, dan tidak dibagikan tanpa izin.</p>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <button id="acceptBtn" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Saya Mengerti</button>
        </div>
      </div>
    </div>

    <script>
      const modal = document.getElementById('securityModal');
      const closeBtn = document.getElementById('closeBtn');
      const acceptBtn = document.getElementById('acceptBtn');

      function closeModal(){
        modal.classList.add('hidden');
        try{ localStorage.setItem('securityModalSeen','1') }catch(e){}
      }

      closeBtn.addEventListener('click', closeModal);
      acceptBtn.addEventListener('click', closeModal);

      // tutup kalau klik di luar konten modal
      modal.addEventListener('click', (e) => {
        if(e.target === modal) closeModal();
      });

      // optional: jangan tampilkan lagi jika sudah pernah dilihat
      try{
        if(localStorage.getItem('securityModalSeen') === '1'){
          modal.classList.add('hidden');
        }
      }catch(e){}
    </script>
</div>