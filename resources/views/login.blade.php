<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laravel Template Admin - Login</title>
        <link rel="icon" type="image/png" href="{{asset('assets/images/favicon.png')}}" sizes="16x16">
        <!-- google fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <!-- remix icon font css  -->
        <link rel="stylesheet" href="{{asset('assets/css/remixicon.css')}}">
        <!-- Apex Chart css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/apexcharts.css')}}">
        <!-- Data Table css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/dataTables.min.css')}}">
        <!-- Text Editor css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/editor-katex.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/lib/editor.atom-one-dark.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/lib/editor.quill.snow.css')}}">
        <!-- Date picker css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/flatpickr.min.css')}}">
        <!-- Calendar css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/full-calendar.css')}}">
        <!-- Vector Map css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/jquery-jvectormap-2.0.5.css')}}">
        <!-- Popup css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/magnific-popup.css')}}">
        <!-- Slick Slider css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/slick.css')}}">
        <!-- prism css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/prism.css')}}">
        <!-- file upload css -->
        <link rel="stylesheet" href="{{asset('assets/css/lib/file-upload.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/lib/audioplayer.css')}}">
        <!-- main css -->
        <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
      </head>
  <body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white">

<section class="bg-white dark:bg-dark-2 flex flex-wrap min-h-[100vh]">  
    <div class="lg:w-1/2 lg:block hidden">
        <div class="flex items-center flex-col h-full justify-center">
            <img src="assets/images/auth/auth-img.png" alt="">
        </div>
    </div>
    <div class="lg:w-1/2 py-8 px-6 flex flex-col justify-center">
        <div class="lg:max-w-[464px] mx-auto w-full">
            <div>
                <a href="index.html" class="mb-2.5 max-w-[290px]">
                    <img src="assets/images/logo.png" alt="">
                </a>
                <h4 class="mb-3">Sign In to your Account</h4>
                <p class="mb-8 text-secondary-light text-lg">Welcome back! please enter your detail</p>
            </div>
            <form action="#">
                <div class="icon-field mb-4 relative">
                    <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input type="email" class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl" placeholder="Email">
                </div>
                <div class="relative mb-5">
                    <div class="icon-field">
                        <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span> 
                        <input type="password" class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl" id="your-password" placeholder="Password">
                    </div>
                    <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#your-password"></span>
                </div>
                <div class="mt-7">
                    <div class="flex justify-between gap-2">
                        <div class="flex items-center">
                            <input class="form-check-input border border-neutral-300" type="checkbox" value="" id="remeber">
                            <label class="ps-2" for="remeber">Remember me </label>
                        </div>
                        <a href="javascript:void(0)" class="text-primary-600 font-medium hover:underline">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary justify-center text-sm btn-sm px-3 py-4 w-full rounded-xl mt-8"> Sign In</button>

                <div class="mt-8 text-center text-sm">
                    <p class="mb-0">Don't have an account? <a href="{{route('register')}}" class="text-primary-600 font-semibold hover:underline">Sign Up</a></p>
                </div>
                
            </form>
        </div>
    </div>
</section>


<!-- jQuery library js -->
<script src="{{asset('assets/js/lib/jquery-3.7.1.min.js')}}"></script>
<!-- Apex Chart js -->
<script src="{{asset('assets/js/lib/apexcharts.min.js')}}"></script>
<!-- Data Table js -->
<script src="{{asset('assets/js/lib/simple-datatables.min.js')}}"></script>
<!-- Iconify Font js -->
<script src="{{asset('assets/js/lib/iconify-icon.min.js')}}"></script>
<!-- jQuery UI js -->
<script src="{{asset('assets/js/lib/jquery-ui.min.js')}}"></script>
<!-- Vector Map js -->
<script src="{{asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- Popup js -->
<script src="{{asset('assets/js/lib/magnifc-popup.min.js')}}"></script>
<!-- Slick Slider js -->
<script src="{{asset('assets/js/lib/slick.min.js')}}"></script>
<!-- prism js -->
<script src="{{asset('assets/js/lib/prism.js')}}"></script>
<!-- file upload js -->
<script src="{{asset('assets/js/lib/file-upload.js')}}"></script>
<!-- audioplayer -->
<script src="{{asset('assets/js/lib/audioplayer.js')}}"></script>
<script src="{{asset('assets/js/flowbite.min.js')}}"></script>
<!-- main js -->
<script src="{{asset('assets/js/app.js')}}"></script>

<script>
      // ================== Password Show Hide Js Start ==========
      function initializePasswordToggle(toggleSelector) {
        $(toggleSelector).on('click', function() {
            $(this).toggleClass("ri-eye-off-line");
            var input = $($(this).attr("data-toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }
    // Call the function
    initializePasswordToggle('.toggle-password');
  // ========================= Password Show Hide Js End ===========================
</script>

</body>
</html>
