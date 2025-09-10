<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">
  
    @include('partials.head')
    @yield('styles')

  <body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white">
    @include('partials.left-sidebar')
    <main class="dashboard-main">
    @include('partials.header')
    @yield('content')
    @include('partials.footer')
    </main>
    
    @include('sweetalert::alert')
    @include('partials.scripts')
  </body>
</html>