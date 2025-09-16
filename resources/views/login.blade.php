@extends('layout.guest')

@section('content')
<section class="bg-white dark:bg-dark-2 flex flex-wrap justify-center min-h-[100vh]">  
    <div class="lg:w-1/2 lg:block hidden">
        <div class="flex items-center flex-col h-full justify-center">
            <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="">
        </div>
    </div>
    <div class="lg:w-1/2 py-8 px-6 flex flex-col justify-center">
        <div class="lg:max-w-[464px] mx-auto w-full">
            <div>
                <a href="#" class="mb-2.5 max-w-[290px]">
                    <img src="{{ asset('assets/images/Logo_PLN_Nusantara_Power.png') }}" alt="">
                </a>
                <h4 class="mb-3">Selamat Datang</h4>
                <p class="mb-8 text-secondary-light text-lg">Visitor Management System PLN NP Office 18</p>
            </div>

            {{-- Form Login --}}
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Email --}}
                @include('components.input', [
                    'type' => 'email',
                    'name' => 'email',
                    'icon' => 'mage:email',
                    'placeholder' => 'Email',
                    'required' => true,
                    'autofocus' => true
                ])
                
                
                {{-- Password --}}
                @include('components.input', [
                    'type' => 'password',
                    'name' => 'password',
                    'icon' => 'ri:lock-line',
                    'placeholder' => 'Password',
                    'required' => true,
                    'id' => 'password'
                ])

                {{-- Submit --}}
                @include('components.button', [
                    'text' => 'Masuk',
                    'type' => 'submit',
                    'variant' => 'primary',
                    'size' => 'md',
                    'class' => 'w-full mt-6'
                ])

            </form>
        </div>
    </div>
</section>

<script>
function initializePasswordToggle(toggleSelector) {
    document.querySelectorAll(toggleSelector).forEach(function(el) {
        el.addEventListener('click', function() {
            this.classList.toggle("ri-eye-off-line");
            var input = document.querySelector(this.getAttribute("data-toggle"));
            if (input.getAttribute("type") === "password") {
                input.setAttribute("type", "text");
            } else {
                input.setAttribute("type", "password");
            }
        });
    });
}
initializePasswordToggle('.toggle-password');
</script>


@endsection