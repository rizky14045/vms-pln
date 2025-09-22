@extends('layout.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Daftar Area</h2>

    <!-- Tombol Tambah Area -->
    @include('components.button', [
        'text' => 'Tambah Area',
        'type' => 'button',
        'variant' => 'primary',
        'size' => 'md',
        'class' => 'mt-6',
        'link' => route('areas.create')
    ])

    <!-- List Area -->
    <div id="areaList" class="space-y-4 mt-4">
        <div class="space-y-4">
            @foreach ($areas as $area)
                @include('components.area-item', ['area' => $area])
            @endforeach
        </div>
    </div>
</div>

<script>
document.querySelectorAll(".btnToggle").forEach(function (btn) {
    btn.addEventListener("click", function () {
        // Ambil wrapper utama area-item
        let wrapper = btn.closest(".p-4"); 
        // Ambil div.children langsung di bawah wrapper
        let children = wrapper.querySelector(":scope > .children");

        if (children) {
            let isExpanded = btn.getAttribute("aria-expanded") === "true";

            if (isExpanded) {
                children.classList.add("hidden");
                btn.setAttribute("aria-expanded", "false");
                btn.textContent = "▼";
            } else {
                children.classList.remove("hidden");
                btn.setAttribute("aria-expanded", "true");
                btn.textContent = "▲";
            }
        }
    });
});
</script>

@endsection

