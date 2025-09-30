@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
</style>
@stop
@section('content')
<div class="dashboard-main-body">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
      <h6 class="font-semibold mb-0 dark:text-white">Profile</h6>
    </div>
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
          <div class="col-span-12 lg:col-span-4">
              <div class="user-grid-card relative border border-neutral-200 dark:border-neutral-600 rounded-2xl overflow-hidden bg-white dark:bg-neutral-700 h-full">
                  <div class="pb-6 ms-6 mb-6 me-6">
                      <div class="mt-6">
                          <h6 class="text-xl mb-4">Personal Info</h6>
                          <ul>
                              <li class="flex items-center gap-1 mb-3">
                                  <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200">NID</span>
                                  <span class="w-[70%] text-secondary-light font-medium">: {{Auth::user()->nid}}</span>
                              </li>
                              <li class="flex items-center gap-1 mb-3">
                                  <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200">Nama</span>
                                  <span class="w-[70%] text-secondary-light font-medium">: {{Auth::user()->name}}</span>
                              </li>
                              <li class="flex items-center gap-1 mb-3">
                                  <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200"> Email</span>
                                  <span class="w-[70%] text-secondary-light font-medium">: {{Auth::user()->email}}</span>
                              </li>
                              <li class="flex items-center gap-1 mb-3">
                                  <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200"> Nomor Telepon</span>
                                  <span class="w-[70%] text-secondary-light font-medium">: {{Auth::user()->phone}}</span>
                              </li>
                             
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-span-12 lg:col-span-8">
              <div class="card h-full border-0">
                  <div class="card-body p-6">
                    <form action="{{route('change-password')}}" method="POST">
                      @csrf
                      @method('PUT')
                      <ul class="tab-style-gradient flex flex-wrap text-sm font-medium text-center mb-5" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                          <li class="" role="presentation">
                              <button class="py-2.5 px-4 border-t-2 font-semibold text-base inline-flex items-center gap-3 text-neutral-600 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="change-password-tab" data-tabs-target="#change-password" type="button" role="tab" aria-controls="change-password" aria-selected="false">
                                  Change Password
                              </button>
                          </li>
                      </ul>
          
                      <div id="default-tab-content">
                      
                        <div class="hidden" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                            <div class="mb-5">
                                <label for="your-password" class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Password Lama <span class="text-danger-600">*</span></label>
                                <div class="relative">
                                    <input type="password" class="form-control rounded-lg" id="old-password" placeholder="Masukan Password Lama" name="old_password">
                                    <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#old-password"></span>
                                    @if($errors->has('old_password'))
                                        <div style="color:red" class="text-red-500 absolute text-sm">{{ $errors->first('old_password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-5">
                                <label for="your-password" class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Password Baru <span class="text-danger-600">*</span></label>
                                <div class="relative">
                                    <input type="password" class="form-control rounded-lg" id="your-password" placeholder="Masukan Password Baru" name="new_password">
                                    <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#your-password"></span>
                                    @if($errors->has('new_password'))
                                        <div style="color:red" class="text-red-500 absolute text-sm">{{ $errors->first('new_password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-5">
                                <label for="confirm-password" class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Konfirmasi Password <span class="text-danger-600">*</span></label>
                                <div class="relative">
                                    <input type="password" class="form-control rounded-lg" id="confirm-password" placeholder="Konfirmasi Password" name="confirm_password">
                                    <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#confirm-password"></span>
                                    @if($errors->has('confirm_password'))
                                        <div style="color:red" class="text-red-500 absolute text-sm">{{ $errors->first('confirm_password') }}</div>
                                    @endif
                                </div>
                                
                            </div>
                            <div class="flex justify-end">

                                @include('components.button', [
                                  'text' => 'Ubah',
                                  'type' => 'submit',
                                  'variant' => 'primary',
                                  'size' => 'md',
                                  ])
                            </div>
                        </div> 
                      </div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection
@section('scripts')
  <script>
  // Toggle for old_password
  document.querySelector('[data-toggle="#old-password"]').addEventListener('click', function() {
    const input = document.getElementById('old-password');
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      this.classList.remove('ri-eye-line');
      this.classList.add('ri-eye-off-line');
    } else {
      input.type = 'password';
      this.classList.remove('ri-eye-off-line');
      this.classList.add('ri-eye-line');
    }
  });

  // Toggle for new_password
  document.querySelector('[data-toggle="#your-password"]').addEventListener('click', function() {
    const input = document.getElementById('your-password');
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      this.classList.remove('ri-eye-line');
      this.classList.add('ri-eye-off-line');
    } else {
      input.type = 'password';
      this.classList.remove('ri-eye-off-line');
      this.classList.add('ri-eye-line');
    }
  });

  // Toggle for confirm_password
  document.querySelector('[data-toggle="#confirm-password"]').addEventListener('click', function() {
    const input = document.getElementById('confirm-password');
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      this.classList.remove('ri-eye-line');
      this.classList.add('ri-eye-off-line');
    } else {
      input.type = 'password';
      this.classList.remove('ri-eye-off-line');
      this.classList.add('ri-eye-line');
    }
  });
  </script>
@endsection