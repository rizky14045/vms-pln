<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
      <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
      <a href="index.html" class="sidebar-logo">
        <img src="{{ asset('assets/logo.png') }}" alt="site logo" class="light-logo">
        <img src="{{ asset('assets/logo.png') }}" alt="site logo" class="dark-logo">
        <img src="{{ asset('assets/logo.ico') }}" alt="site logo" class="logo-icon">
      </a>
    </div>
    <div class="sidebar-menu-area">
      <ul class="sidebar-menu" id="sidebar-menu">
        @can('view dashboard')
        <li>
          <a href="{{route('dashboard')}}">
            <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
            <span>Dashboard</span>
          </a>
        </li>
        @endcan
        @can('approval employee')
        <li>
            <a href="{{ route('registered.index') }}">
                <iconify-icon icon="solar:users-group-rounded-broken" class="menu-icon"></iconify-icon>
                <span>Register Employee</span>
            </a>
        </li>
        @endcan
        @can('approval visitor')
        <li>
          <a href="{{route('registered.index.visitor')}}">
            <iconify-icon icon="solar:users-group-rounded-broken" class="menu-icon"></iconify-icon>
            <span>Register Visitor</span>
          </a>
        </li>
        @endcan
        @can('manage area')
        <li>
          <a href="{{route('areas.index')}}">
            <iconify-icon icon="solar:buildings-2-bold" class="menu-icon"></iconify-icon>
            <span>Area</span>
          </a>
        </li>
        @endcan
        @can('manage device')
        <li>
          <a href="{{route('devices.index')}}">
            <iconify-icon icon="solar:smartphone-2-linear" class="menu-icon"></iconify-icon>
            <span>Device</span>
          </a>
        </li>
        @endcan
        @role('super-admin')
        <li>
          <a href="{{route('products.index')}}">
            <iconify-icon icon="solar:box-minimalistic-broken" class="menu-icon"></iconify-icon>
            <span>Product</span>
          </a>
        </li>
        @endrole
        @can('manage visitor card')
        <li>
          <a href="{{route('visitor-cards.index')}}">
            <iconify-icon icon="solar:card-2-outline" class="menu-icon"></iconify-icon>
            <span>Kartu Visitor</span>
          </a>
        </li>
        @endcan
      </ul>
    </div>
  </aside>