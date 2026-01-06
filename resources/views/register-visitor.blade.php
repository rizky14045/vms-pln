<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Visitor</title>
  <script src="{{asset('assets/css/tailwind.css')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
  <!-- axios -->
  <script src="{{asset('assets/js/axios.min.js')}}"></script>
  <!-- face-api.js -->
</head>
<body class="min-h-screen w-screen flex items-center justify-center p-2 md:p-4" style="background-color: #14A2BA">

  @include('components.security-modal')

  <!-- Container Form -->
  <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-4 md:p-8 relative overflow-y-auto">
    
    <!-- Logo -->
    <div class="flex justify-center pb-3">
        <img src="{{asset('assets/logo.png')}}" alt="Logo" class="h-12 md:h-16">
    </div>

    <!-- Judul -->
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register Visitor</h2>
     @if (\Session::has('success'))
        <div class="w-full flex justify-center">
          <div class="alert alert-emerald bg-emerald-100 dark:bg-emerald-600/25 text-emerald-600 dark:text-emerald-400 border-emerald-100 
                      px-6 py-[11px] mb-0 text-lg rounded-lg 
                      flex items-center gap-2 text-center"
              role="alert">
              <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
              {!! \Session::get('success') !!}
          </div>
        </div>
      @endif
     @if (\Session::has('info'))
        <div class="w-full flex justify-center">
          <div class="alert alert-sky bg-sky-100 dark:bg-sky-600/25 text-sky-600 dark:text-sky-400 border-sky-100 
                      px-6 py-[11px] mb-0 text-lg rounded-lg 
                      flex items-center gap-2 text-center"
              role="alert">
              <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
              {!! \Session::get('info') !!}
          </div>
        </div>
      @endif


    <!-- Form -->
    <form class="space-y-4" method="POST" action="{{ route('register-visitor-request') }}" enctype="multipart/form-data">
      @csrf
      <div>
          @include('components.input', [
              'type' => 'text',
              'name' => 'nid',
              'id' => 'nid',
              'placeholder' => 'Nomor Identitas/Identity Number (NID/Nomor Induk Pegawai)',
              'required' => true,
              'autofocus' => true,
              'label' => 'NID',
          ])
          <p id="nid-message" class="text-sm mt-1"></p>
      </div>
      <p class="text-xs text-gray-600 italic mt-2">
          Tekan <strong>Check Data</strong> untuk memeriksa apakah nomor Identitas sudah pernah terdaftar.
          Jika data sudah ada di sistem, maka form akan otomatis menampilkan data sebelumnya. Jika belum pernah terdaftar, maka Anda dapat melanjutkan pengisian form seperti biasa.
      </p>
      <div class="flex justify-end">
          @include('components.button', [
              'text' => 'Check Data',
              'type' => 'button',
              'variant' => 'primary',
              'size' => 'sm',
              'class' => 'bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-2 rounded-md transition '
          ])
      </div>
      <div>
          @include('components.input', [
              'type' => 'text',
              'name' => 'name',
              'id' => 'name',
              'placeholder' => 'Nama Lengkap',
              'required' => true,
              'autofocus' => true,
              'label' => 'Nama/Name',
          ])
      </div>
      <div>
          @include('components.input', [
              'type' => 'text',
              'name' => 'phone',
              'id' => 'phone',
              'placeholder' => '628xxxxxxx',
              'required' => true,
              'autofocus' => true,
              'label' => 'Nomor HP/Phone Number',
          ])
      </div>
      <div>
          @include('components.input', [
              'type' => 'text',
              'name' => 'company',
              'id' => 'company',
              'placeholder' => 'Nama Perusahaan',
              'required' => true,
              'autofocus' => true,
              'label' => 'Nama Perusahaan/Company Name',
          ])
      </div>
      <div>
          @include('components.input', [
              'type' => 'text',
              'name' => 'purpose_of_visit',
              'id' => 'purpose_of_visit',
              'placeholder' => 'Tujuan',
              'required' => true,
              'autofocus' => true,
              'label' => 'Tujuan/Purpose',
          ])
      </div>

      <div>
          @include('components.input', [
              'type' => 'text',
              'name' => 'pic_name',
              'id' => 'pic_name',
              'placeholder' => 'PIC yang dikunjungi',
              'required' => true,
              'autofocus' => false,
              'label' => 'PIC yang dikunjungi/Person In Charge to Visit',
          ])
      </div>

      <div>
          @include('components.input', [
              'type' => 'text',
              'name' => 'pic_phone',
              'id' => 'pic_phone',
              'placeholder' => 'Nomor HP PIC yang dikunjungi',
              'required' => false,
              'autofocus' => false,
              'label' => 'Nomor HP PIC yang dikunjungi/Phone Number of Person In Charge to Visit',
          ])
      </div>

      <div class="flex flex-col items-center justify-center gap-4">
        <h2 class="text-lg font-semibold mb-2">Upload Image</h2>

        <!-- Preview Gambar -->
        <img
          id="imagePreview"
          class="w-full max-w-sm h-auto border rounded-lg shadow hidden"
          alt="Preview Gambar"
        />

        <!-- Input File -->
        <input
          type="file"
          id="fileInput"
          name="person_image"
          accept="image/*"
          class="hidden"
        />

        <button
          type="button"
          onclick="document.getElementById('fileInput').click()"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        >
          Select Image
        </button>

        <div class="text-gray-600 text-sm text-center max-w-xs">
          Pastikan wajah terlihat jelas dan tidak buram.<br>
          Format yang didukung: JPG, JPEG, PNG.<br>
          Resolusi minimal: <span class="font-semibold">600×800 px</span>.<br>
          Resolusi maksimal: <span class="font-semibold">1920x1080 px</span>.<br>
          Ukuran maksimal file: <span class="font-semibold">2 MB</span>.
        </div>
        
        @if($errors->has('person_image'))
          <div class="text-red-500 text-sm">
            {{ $errors->first('person_image') }}
          </div>
        @endif
      </div>

      <!-- Tombol Aksi -->
      <div class="flex gap-4">
        <a href="{{route('home')}}"
          class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition text-center inline-block">
          Kembali
        </a>
        <button type="submit" 
          class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 rounded-lg transition">
          Daftar
        </button>
      </div>
    </form>
  </div>

<script>
  const checkNidBtn = document.querySelector('button[type="button"]')
  const nidInput = document.getElementById('nid')
  const nameInput = document.getElementById('name')
  const emailInput = document.getElementById('email')
  const phoneInput = document.getElementById('phone')
  const nidMessage = document.getElementById('nid-message')

  const fileInput = document.getElementById("fileInput");
  const imagePreview = document.getElementById("imagePreview");

  fileInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file && file.type.startsWith("image/")) {
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreview.src = e.target.result;
        imagePreview.classList.remove("hidden");
      };
      reader.readAsDataURL(file);
    } else {
      alert("Silakan pilih file gambar yang valid.");
    }
  });

  // 🔹 Fungsi check NID
  checkNidBtn.addEventListener('click', async () => {
    nameInput.value = "" 
    emailInput.value = "" 
    phoneInput.value = "" 
    const nid = nidInput.value.trim()
    nidMessage.textContent = ""
    nidMessage.className = "text-sm mt-1" 

    if (!nid) {
      nidMessage.textContent = "Silakan masukkan NID terlebih dahulu"
      nidMessage.classList.add("text-red-600")
      return
    }

    try {
      const res = await axios.get("{{ url('api/v1/get-user-by-nid') }}/" + nid)
      nameInput.value = res.data.data.user.name
      emailInput.value = res.data.data.user.email
      phoneInput.value = res.data.data.user.phone
      nidMessage.textContent = res.data.message
      nidMessage.classList.add("text-green-600")
    } catch (err) {
      nidMessage.textContent = err.response.data.message
      nidMessage.classList.add("text-red-600")
    }
  })

</script>

</body>
</html>
