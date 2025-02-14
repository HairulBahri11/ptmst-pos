@extends('app')
@section('active2', 'active')
@section('title', 'Customer')
@section('content')
    <div class="container mx-auto mt-6 px-4">
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <!-- Sisi kiri: Tabel Data Customer -->
            <div class="bg-white shadow-lg rounded-lg p-6 md:w-2/3 w-full h-auto">
                <h3 class="text-xl font-semibold mb-4">Data Customer</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200" id="myTable">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-2 px-4 text-left">Kode</th>
                                <th class="py-2 px-4 text-left">Nama</th>
                                <th class="py-2 px-4 text-left">Alamat</th>
                                <th class="py-2 px-4 text-left">No. Telepon</th>
                                <th class="py-2 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr class="border-b border-gray-300">
                                    <td class="py-2 px-4">{{ $customer->kode }}</td>
                                    <td class="py-2 px-4">{{ $customer->name }}</td>
                                    <td class="py-2 px-4">{{ $customer->alamat }}</td>
                                    <td class="py-2 px-4">{{ $customer->telp }}</td>
                                    <td class="py-2 px-4">
                                        <button class="text-yellow-500 hover:text-yellow-600 edit-btn"
                                            data-id="{{ $customer->id }}" data-nama="{{ $customer->name }}"
                                            data-alamat="{{ $customer->alamat }}" data-no_telp="{{ $customer->telp }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-500 hover:text-red-600 delete-btn"
                                            data-id="{{ $customer->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-form-{{ $customer->id }}"
                                            action="{{ route('customers.destroy', $customer->id) }}" method="POST"
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

            <!-- Sisi kanan: Form Input Customer -->
            <div class="bg-white shadow-lg rounded-lg p-6 md:w-1/3 w-full h-auto">
                <h3 class="text-xl font-semibold mb-4">Tambah / Edit Customer</h3>
                <form id="customer-form" action="{{ route('customers.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="customer_id" name="id">

                    <div>
                        <label for="nama" class="block text-gray-700 font-medium">Nama:</label>
                        <input type="text" id="nama" name="nama"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div>
                        <label for="alamat" class="block text-gray-700 font-medium">Alamat:</label>
                        <input type="text" id="alamat" name="alamat"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div>
                        <label for="no_telp" class="block text-gray-700 font-medium">No. Telepon:</label>
                        <input type="text" id="no_telp" name="no_telp"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
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

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script untuk Edit dan Hapus Customer -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Edit Customer
            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("customer_id").value = this.dataset.id;
                    document.getElementById("nama").value = this.dataset.nama;
                    document.getElementById("alamat").value = this.dataset.alamat;
                    document.getElementById("no_telp").value = this.dataset.no_telp;
                });
            });

            // Hapus Customer dengan SweetAlert
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let customerId = this.dataset.id;
                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Data customer akan dihapus secara permanen!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${customerId}`).submit();
                        }
                    });
                });
            });

            // Validasi sebelum submit form
            document.getElementById("customer-form").addEventListener("submit", function(event) {
                let nama = document.getElementById("nama").value.trim();
                let alamat = document.getElementById("alamat").value.trim();
                let no_telp = document.getElementById("no_telp").value.trim();

                if (!nama || !alamat || !no_telp) {
                    event.preventDefault();
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Semua field harus diisi!",
                    });
                }
            });

            // Tampilkan SweetAlert jika ada notifikasi sukses
            @if (session('success'))
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection
