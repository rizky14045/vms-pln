<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
      <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
      <a href="index.html" class="sidebar-logo">
        
        <img src="assets/images/logo.png" alt="site logo" class="light-logo">
        <img src="assets/images/logo-light.png" alt="site logo" class="dark-logo">
        <img src="{{ asset('assets/logo.ico') }}" alt="site logo" class="logo-icon">
      </a>
    </div>
    <div class="sidebar-menu-area">
      <ul class="sidebar-menu" id="sidebar-menu">
        <li>
          <a href="{{route('dashboard')}}">
            <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="chat-message.html">
            <iconify-icon icon="solar:users-group-rounded-broken" class="menu-icon"></iconify-icon>
            <span>Register Employee</span>
          </a>
        </li>
        <li>
          <a href="{{route('areas.index')}}">
            <iconify-icon icon="solar:buildings-2-bold" class="menu-icon"></iconify-icon>
            <span>Area</span>
          </a>
        </li>
        <li>
          <a href="{{route('devices.index')}}">
            <iconify-icon icon="solar:smartphone-2-linear" class="menu-icon"></iconify-icon>
            <span>Device</span>
          </a>
        </li>
        
      </ul>
    </div>
  </aside>