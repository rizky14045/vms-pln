<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visitor Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen bg-gray-900">

  <!-- Background -->
  <div class="relative h-screen w-screen bg-cover bg-center" 
       style="background-image: url('https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80');">
    <div class="absolute inset-0 bg-black bg-opacity-70"></div>

    <!-- Logo kiri atas -->
    <div class="absolute top-4 left-6 z-20 flex items-center space-x-2">
      <img src="{{asset('assets/logo.png')}}" alt="Logo">
    </div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col justify-center items-center h-full text-center text-white px-4">
      <h1 class="text-4xl md:text-5xl font-bold mb-3">Selamat Datang</h1>
      <p class="text-lg md:text-xl mb-6">Visitor Management System</p>
      
      <!-- Waktu Live -->
      <div id="clock" class="text-lg font-mono mb-6"></div>

      <!-- Buttons -->
      <div class="space-x-4">
        <a class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded" href="{{route('register-visitor')}}">Mulai</a>
      </div>
    </div>
  </div>

  <!-- Script Clock -->
  <script>
    function updateClock() {
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const now = new Date();
      const dateStr = now.toLocaleDateString('id-ID', options);
      const timeStr = now.toLocaleTimeString('id-ID');
      document.getElementById('clock').textContent = `${dateStr} | ${timeStr}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>
