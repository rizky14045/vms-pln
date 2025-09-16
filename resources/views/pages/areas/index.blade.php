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
        'class' => 'mt-6'
    ])

    <!-- Container Form Tambah Area -->
    <div id="formContainer" class="hidden mt-6">
        <form id="areaForm" class="bg-white dark:bg-neutral-800 p-6 rounded-xl shadow-md">
            <input type="hidden" name="parent_id" id="parent_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nama Area</label>
                <input type="text" name="name" id="areaName"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 p-2 dark:bg-neutral-700 dark:border-neutral-600"
                    placeholder="Masukkan nama area" required>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" id="btnCancel"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- List Area -->
    <div id="areaList" class="mt-8 space-y-3">
        <!-- Contoh area -->
        <div class="p-4 border rounded-lg bg-gray-50 dark:bg-neutral-700">
            <div class="flex justify-between items-center">
                <span class="font-medium">Area Utama 1</span>
                <button class="btnAddChild bg-green-600 text-white px-3 py-1 rounded-md"
                    data-parent="1">+ Tambah Anak</button>
            </div>
            <div class="ml-6 mt-2 text-sm text-gray-600 dark:text-gray-300">
                <ul class="list-disc">
                    <li>Sub Area A <button class="btnAddChild text-blue-500 ml-2" data-parent="2">+ Tambah Anak</button></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    // tombol tambah area utama
    $("#btnAddRoot").click(function () {
        $("#formContainer").removeClass("hidden");
        $("#parent_id").val(""); // tanpa parent
        $("#areaName").val("");
    });

    // tombol tambah anak area
    $(document).on("click", ".btnAddChild", function () {
        let parentId = $(this).data("parent");
        $("#formContainer").removeClass("hidden");
        $("#parent_id").val(parentId); // isi dengan parent id
        $("#areaName").val("");
    });

    // batal
    $("#btnCancel").click(function () {
        $("#formContainer").addClass("hidden");
        $("#parent_id").val("");
        $("#areaName").val("");
    });

    // submit form
    $("#areaForm").submit(function (e) {
        e.preventDefault();

        let name = $("#areaName").val();
        let parentId = $("#parent_id").val();

        // contoh: kirim AJAX
        $.ajax({
            url: "#",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                parent_id: parentId
            },
            success: function (res) {
                alert("Area berhasil ditambahkan!");
                $("#formContainer").addClass("hidden");
                $("#areaForm")[0].reset();

                // TODO: render ulang daftar area tanpa reload page
            },
            error: function (xhr) {
                alert("Gagal menambahkan area");
            }
        });
    });
});
</script>
@endsection
