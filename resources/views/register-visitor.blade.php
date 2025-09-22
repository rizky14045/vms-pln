<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Visitor</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen w-screen flex items-center justify-center p-2 md:p-4" style="background-color: #14A2BA;">

  <!-- Container Form -->
  <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-4 md:p-8 relative overflow-y-auto">
    
    <!-- Logo -->
    <div class="flex justify-center pb-3">
        <img src="{{asset('assets/logo.png')}}" alt="Logo" class="h-12 md:h-16">
    </div>

    <!-- Judul -->
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register Visitor</h2>

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
          @include('components.areaText', [
              'name' => 'purpose_of_visit',
              'id' => 'purpose_of_visit',
              'required' => true,
              'autofocus' => true,
              'label' => 'Tujuan Kunjungan',
          ])
      </div>

      <!-- Kamera & Hasil Foto -->
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Hasil Foto -->
        <div class="flex-1 flex flex-col items-center justify-center">
          <h2 class="text-lg font-semibold mb-4">Hasil Foto</h2>
          <canvas id="snapshot" class="w-full max-w-sm h-auto border rounded-lg shadow mb-4"></canvas>
          @if($errors->has('person_image'))
              <div style="color:red" class="text-red-500 absolute text-sm">{{ $errors->first('person_image') }}</div>
          @endif
          <input type="file" id="fileInput" name="person_image" class="hidden">
        </div>

        <!-- Preview Kamera -->
        <div class="flex-1 flex flex-col items-center justify-center" id="camera-section">
          <h2 class="text-lg font-semibold mb-4">Preview Kamera</h2>
          <video id="preview" autoplay playsinline class="w-full max-w-sm h-auto border rounded-lg shadow bg-black"></video>
        </div>
      </div>

      <!-- Tombol Capture -->
      <div class="mt-4 flex justify-center w-full">
        <button id="capture" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
          Ambil Foto
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
  const video = document.getElementById('preview');
  const canvas = document.getElementById('snapshot');
  const captureButton = document.getElementById('capture');
  const fileInput = document.getElementById('fileInput');
  const cameraSection = document.getElementById('camera-section');

  async function startCamera() {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true });
      video.srcObject = stream;
    } catch (error) {
      console.error('Error accessing camera:', error);
      cameraSection.innerHTML = '<p class="text-red-500">Tidak dapat mengakses kamera. Periksa izin kamera di browser Anda.</p>';
    }
  }

  function capturePhoto() {
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    canvas.toBlob((blob) => {
      const file = new File([blob], 'photo.png', { type: 'image/png' });
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      fileInput.files = dataTransfer.files;
    }, 'image/png');
  }

  captureButton.addEventListener('click', (e) => {
    e.preventDefault();
    capturePhoto();
  });

  window.addEventListener('load', startCamera);
</script>
</body>
</html>
