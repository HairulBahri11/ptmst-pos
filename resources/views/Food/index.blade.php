@extends('app')
@section('active3', 'active')
@section('title', 'Food Items')
@section('content')
    <div class="container mx-auto mt-6 px-4">
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <!-- Sisi kiri: Tabel Data Makanan -->
            <div class="bg-white shadow-lg rounded-lg p-6 md:w-2/3 w-full h-auto">
                <h3 class="text-xl font-semibold mb-4">Data Makanan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200" id="myTable">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-2 px-4 text-left">Kode</th>
                                <th class="py-2 px-4 text-left">Nama</th>
                                <th class="py-2 px-4 text-left">Harga</th>
                                <th class="py-2 px-4 text-left">Gambar</th>
                                <th class="py-2 px-4 text-left">Status</th>
                                <th class="py-2 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($foods as $food)
                                <tr class="border-b border-gray-300">
                                    <td class="py-2 px-4">{{ $food->kode }}</td>
                                    <td class="py-2 px-4">{{ $food->nama }}</td>
                                    <td class="py-2 px-4">Rp {{ number_format($food->harga, 2, ',', '.') }}</td>
                                    <td class="py-2 px-4">
                                        @if ($food->image)
                                            <img src="{{ asset('storage/img/' . $food->image) }}" alt="Gambar"
                                                class="h-14 w-14 rounded">
                                        @else
                                            <span class="text-gray-500">No Image</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4">
                                        <span class="{{ $food->status ? 'text-green-500' : 'text-red-500' }}">
                                            {{ $food->status ? 'Tersedia' : 'Tidak Tersedia' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4">
                                        <button class="text-yellow-500 hover:text-yellow-600 edit-btn"
                                            data-id="{{ $food->id }}" data-nama="{{ $food->nama }}"
                                            data-harga="{{ $food->harga }}" data-status="{{ $food->status }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-500 hover:text-red-600 delete-btn"
                                            data-id="{{ $food->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-form-{{ $food->id }}"
                                            action="{{ route('foods.destroy', $food->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sisi kanan: Form Input Makanan -->
            <div class="bg-white shadow-lg rounded-lg p-6 md:w-1/3 w-full h-auto">
                <h3 class="text-xl font-semibold mb-4">Tambah / Edit Makanan</h3>
                <form id="food-form" action="{{ route('foods.store') }}" method="POST" class="space-y-4"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="food_id" name="id">

                    <div>
                        <label for="nama" class="block text-gray-700 font-medium">Nama:</label>
                        <input type="text" id="nama" name="nama" required
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div>
                        <label for="harga" class="block text-gray-700 font-medium">Harga:</label>
                        <input type="text" id="harga" name="harga" required
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div>
                        <label for="image" class="block text-gray-700 font-medium">Gambar:</label>
                        <input type="file" id="image" name="image"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div>
                        <label for="status" class="block text-gray-700 font-medium">Status:</label>
                        <select id="status" name="status"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="1">Tersedia</option>
                            <option value="0">Tidak Tersedia</option>
                        </select>
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                        <button type="reset"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Reset</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mengisi form saat tombol edit diklik
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const nama = this.getAttribute("data-nama");
                const harga = this.getAttribute("data-harga");
                const status = this.getAttribute("data-status");

                // Mengisi form dengan data yang dipilih
                document.getElementById("food_id").value = id;
                document.getElementById("nama").value = nama;
                document.getElementById("harga").value = harga;
                document.getElementById("status").value = status == "1" ? "1" : "0";

                // Ubah action form untuk edit
                document.getElementById("food-form").action = `/foods/store/${id}`;
            });
        });

        // Konfirmasi penghapusan dengan SweetAlert
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data akan dihapus secara permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            });
        });

        // Notifikasi saat berhasil disimpan
        @if (session('success'))
            Swal.fire({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        @endif

        // Reset form saat tombol reset diklik
        document.querySelector("button[type='reset']").addEventListener("click", function() {
            document.getElementById("food-form").action = "{{ route('foods.store') }}";
            document.getElementById("food_id").value = "";
        });
    });
</script> --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Saat tombol edit diklik, isi form dengan data yang ada
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const nama = this.getAttribute("data-nama");
                const harga = this.getAttribute("data-harga");
                const status = this.getAttribute("data-status");

                // Mengisi form dengan data yang dipilih
                document.getElementById("food_id").value = id;
                document.getElementById("nama").value = nama;
                document.getElementById("harga").value = harga;
                document.getElementById("status").value = status == "1" ? "1" : "0";

                // Ubah tombol submit agar tidak membuat data baru, tetapi memperbarui
                document.getElementById("food-form").action = "{{ route('foods.store') }}";
            });
        });

        // Reset form ke mode tambah saat tombol reset diklik
        document.querySelector("button[type='reset']").addEventListener("click", function() {
            document.getElementById("food-form").action = "{{ route('foods.store') }}";
            document.getElementById("food_id").value = "";
        });
    });
</script>
