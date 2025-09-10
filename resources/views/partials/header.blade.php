<div class="navbar-header border-b border-neutral-200 dark:border-neutral-600">
    <div class="flex items-center justify-between">
      <div class="col-auto">
        <div class="flex flex-wrap items-center gap-[16px]">
          <button type="button" class="sidebar-toggle">
            <iconify-icon icon="heroicons:bars-3-solid" class="icon non-active"></iconify-icon>
            <iconify-icon icon="iconoir:arrow-right" class="icon active"></iconify-icon>
          </button>
          <button type="button" class="sidebar-mobile-toggle">
            <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
          </button>
          <form class="navbar-search">
            <input type="text" name="search" placeholder="Search">
            <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
          </form>
          <!-- <span class="max-w-[244px] w-full p-6 h-3 bg-red-600 text-white flex items-center justify-center rounded-[50px]">tesdgxt</span> -->
        </div>
      </div>
      <div class="col-auto">
        <div class="flex flex-wrap items-center gap-3">
          <button type="button" id="theme-toggle" class="w-10 h-10 bg-neutral-200 dark:bg-neutral-700 dark:text-white rounded-full flex justify-center items-center">
            <span id="theme-toggle-dark-icon" class="hidden">
              <i class="ri-sun-line"></i>
            </span>
            <span id="theme-toggle-light-icon" class="hidden">
              <i class="ri-moon-line"></i>
            </span>
          </button>
          <button data-dropdown-toggle="dropdownProfile" class="flex justify-center items-center rounded-full" type="button">
            <img src="assets/images/user.png" alt="image" class="w-10 h-10 object-fit-cover rounded-full">
          </button>
          <div id="dropdownProfile" class="z-10 hidden bg-white dark:bg-neutral-700 rounded-lg shadow-lg dropdown-menu-sm p-3">
            <div class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 mb-4 flex items-center justify-between gap-2">
              <div>
                <h6 class="text-lg text-neutral-900 font-semibold mb-0">Shahidul Islam</h6>
                <span class="text-neutral-500">Admin</span>
              </div>
              <button type="button" class="hover:text-danger-600">
                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
              </button>
            </div>
            <div class="max-h-[400px] overflow-y-auto scroll-sm pe-2">
              <ul class="flex flex-col">
                <li>
                  <a class="text-black px-0 py-2 hover:text-primary-600 flex items-center gap-4" href="view-profile.html">
                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My Profile
                  </a>
                </li>
                <li>
                  <a class="text-black px-0 py-2 hover:text-primary-600 flex items-center gap-4" href="email.html">
                    <iconify-icon icon="tabler:message-check" class="icon text-xl"></iconify-icon> Inbox
                  </a>
                </li>
                <li>
                  <a class="text-black px-0 py-2 hover:text-primary-600 flex items-center gap-4" href="company.html">
                    <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon> Setting
                  </a>
                </li>
                <li>
                  <a class="text-black px-0 py-2 hover:text-danger-600 flex items-center gap-4" href="javascript:void(0)">
                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>