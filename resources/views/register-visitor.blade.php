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

  <!-- Container Form -->
  <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-4 md:p-8 relative overflow-y-auto">
    
    <!-- Logo -->
    <div class="flex justify-center pb-3">
        <img src="{{asset('assets/logo.png')}}" alt="Logo" class="h-12 md:h-16">
    </div>

    <!-- Judul -->
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register Karyawan</h2>
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
    <form class="space-y-4" method="POST" action="{{ route('register-request') }}" enctype="multipart/form-data">
      @csrf
      <div>
          @include('components.input', [
              'type' => 'text',
              'name' => 'nid',
              'id' => 'nid',
              'placeholder' => 'NID',
              'required' => true,
              'autofocus' => true,
              'label' => 'NID',
          ])
          <p id="nid-message" class="text-sm mt-1"></p>
      </div>
      <div class="flex justify-end">
          @include('components.button', [
              'text' => 'Check NID',
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
              'label' => 'Nama Lengkap',
          ])
      </div>
      <div>
          @include('components.input', [
              'type' => 'email',
              'name' => 'email',
              'id' => 'email',
              'placeholder' => 'Email',
              'required' => true,
              'autofocus' => true,
              'label' => 'Email',
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
              'label' => 'Nomor HP',
          ])
      </div>

      <!-- Kamera & Hasil Foto -->
      <div class="flex flex-col md:flex-row gap-6">
        <div class="flex-1 flex flex-col items-center justify-center">
          <h2 class="text-lg font-semibold mb-4">Hasil Foto</h2>
          <canvas id="snapshot" class="w-full max-w-sm h-auto border rounded-lg shadow mb-4"></canvas>
          @if($errors->has('person_image'))
              <div style="color:red" class="text-red-500 absolute text-sm">{{ $errors->first('person_image') }}</div>
          @endif
          <input type="file" id="fileInput" name="person_image" class="hidden">
        </div>

        <div class="flex-1 flex flex-col items-center justify-center" id="camera-section">
          <h2 class="text-lg font-semibold mb-4">Preview Kamera</h2>
          <video id="preview" autoplay playsinline class="w-full max-w-sm h-auto border rounded-lg shadow bg-black"></video>
        </div>
      </div>

      <!-- Tombol Capture -->
      <div class="mt-4 flex justify-center w-full">
        <button id="capture" class="px-6 py-2 bg-gray-400 text-white rounded-lg shadow cursor-not-allowed" disabled>
          Wajah tidak terdeteksi
        </button>
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
  const video = document.getElementById('preview')
  const canvas = document.getElementById('snapshot')
  const captureButton = document.getElementById('capture')
  const fileInput = document.getElementById('fileInput')
  const cameraSection = document.getElementById('camera-section')
  const checkNidBtn = document.querySelector('button[type="button"]')
  const nidInput = document.getElementById('nid')
  const nameInput = document.getElementById('name')
  const emailInput = document.getElementById('email')
  const phoneInput = document.getElementById('phone')
  const nidMessage = document.getElementById('nid-message')

  let faceDetected = false

  async function startCamera() {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true })
      video.srcObject = stream
    } catch (error) {
      console.error('Error accessing camera:', error)
      cameraSection.innerHTML = '<p class="text-red-500">Tidak dapat mengakses kamera. Periksa izin kamera di browser Anda.</p>'
    }
  }

  // Load face-api models
  async function loadModels() {
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models')
    console.log("Model FaceAPI Loaded ✅")
    detectFaceLoop()
  }

  async function detectFaceLoop() {
    setInterval(async () => {
      const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
      if (detections.length > 0) {
        faceDetected = true
        captureButton.disabled = false
        captureButton.textContent = "Ambil Foto"
        captureButton.classList.remove("bg-gray-400", "cursor-not-allowed")
        captureButton.classList.add("bg-blue-600", "hover:bg-blue-700")
      } else {
        faceDetected = false
        captureButton.disabled = true
        captureButton.textContent = "Wajah tidak terdeteksi"
        captureButton.classList.remove("bg-blue-600", "hover:bg-blue-700")
        captureButton.classList.add("bg-gray-400", "cursor-not-allowed")
      }
    }, 500)
  }

  function capturePhoto() {
    if (!faceDetected) {
      alert("Tidak ada wajah yang terdeteksi. Silakan posisikan wajah Anda di depan kamera.")
      return
    }
    const context = canvas.getContext('2d')
    canvas.width = video.videoWidth
    canvas.height = video.videoHeight
    context.drawImage(video, 0, 0, canvas.width, canvas.height)

    canvas.toBlob((blob) => {
      const file = new File([blob], 'photo.jpeg', { type: 'image/jpeg' })
      const dataTransfer = new DataTransfer()
      dataTransfer.items.add(file)
      fileInput.files = dataTransfer.files
    }, 'image/jpeg')
  }

  captureButton.addEventListener('click', (e) => {
    e.preventDefault()
    capturePhoto()
  })

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

  window.addEventListener('load', async () => {
    await startCamera()
    await loadModels()
  })
</script>

</body>
</html>
