<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Visitor</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen flex items-center justify-center" style="background-color: #14A2BA;">

  <!-- Container Form -->
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8 relative">
    
    <!-- Logo -->
    <div class="flex justify-center pb-3">
        <img src="{{asset('assets/logo.png')}}" alt="Logo">
    </div>

    <!-- Judul -->
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register Visitor</h2>

    <!-- Form -->
    <form class="space-y-4">
      <div>
        <label class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
        <input type="text" placeholder="Masukkan nama" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Email</label>
        <input type="email" placeholder="Masukkan email" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Nomor Telepon</label>
        <input type="text" placeholder="Masukkan nomor telepon" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Tujuan Kunjungan</label>
        <textarea placeholder="Tuliskan tujuan" rows="3"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"></textarea>
      </div>
      <div class="flex gap-4">

          <button type="submit" 
          class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 rounded-lg transition">
          Daftar
        </button>
        <a href="{{route('home')}}"
          class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition text-center inline-block">
          Kembali
         </a>
    </div>
    </form>
  </div>

</body>
</html>
