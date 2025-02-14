@extends('app')
@section('title', 'Transaction')
@section('active1', 'active')

@section('content')
    <div class="container mx-auto mt-6 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Daftar Transaksi -->
            <div class="bg-white shadow-lg rounded-lg p-6 h-full">
                <h3 class="text-xl font-semibold mb-4">Data Transaksi</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200 shadow-md rounded-lg" id="myTable">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-3 px-4 text-left">No Transaksi</th>
                                <th class="py-3 px-4 text-left">Tanggal</th>
                                <th class="py-3 px-4 text-left">Total Bayar</th>
                                <th class="py-3 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-50">
                            @foreach ($transactions as $transaction)
                                <tr class="border-b border-gray-300 hover:bg-gray-100 transition-all duration-200">
                                    <td class="py-3 px-4">{{ $transaction->no_transaksi }}</td>
                                    <td class="py-3 px-4">{{ $transaction->tanggal }}</td>
                                    <td class="py-3 px-4">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 flex space-x-2">
                                        <button
                                            class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition-all duration-200 view-details"
                                            data-id="{{ $transaction->id }}">
                                            Detail
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Transaksi -->
            <div class="bg-white shadow-lg rounded-lg p-6 h-full">
                <h3 class="text-xl font-semibold mb-4">Detail Transaksi</h3>
                <div id="transactionDetail" class="text-gray-700">
                    <p class="text-center text-gray-500">Pilih transaksi untuk melihat detail.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".view-details").forEach(button => {
                button.addEventListener("click", function() {
                    const transactionId = this.dataset.id;

                    fetch(`{{ route('transactions.show', ':id') }}`.replace(':id', transactionId))
                        .then(response => response.json())
                        .then(data => {
                            let detailsHtml = `
                        <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200 w-full max-w-4xl mx-auto">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                                <p><strong class="block sm:inline w-32">No Transaksi:</strong> <span class="text-gray-900 font-medium">${data.no_transaksi}</span></p>
                                <p><strong class="block sm:inline w-32">Nama Customer:</strong> <span class="text-gray-900 font-medium">${data.customer}</span></p>
                                <p><strong class="block sm:inline w-32">Tanggal:</strong> <span class="text-gray-900 font-medium">${data.tanggal}</span></p>
                                <p><strong class="block sm:inline w-32">Subtotal:</strong> <span class="text-green-600 font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(data.subtotal)}</span></p>
                                <p><strong class="block sm:inline w-32">Diskon:</strong> <span class="text-red-500 font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(data.diskon ?? 0)}</span></p>
                                <p><strong class="block sm:inline w-32">Ongkir:</strong> <span class="text-blue-500 font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(data.ongkir ?? 0)}</span></p>
                            </div>

                            <div class="mt-6 bg-gray-100 p-4 rounded-lg flex items-center justify-between">
                                <strong class="text-lg sm:text-xl text-gray-800">Total Bayar:</strong>
                                <span class="text-xl sm:text-2xl font-bold text-green-700">Rp ${new Intl.NumberFormat('id-ID').format(data.total_bayar)}</span>
                            </div>

                            <h4 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Daftar Item:</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse border border-gray-200 shadow-sm rounded-lg text-sm sm:text-base">
                                    <thead class="bg-gray-800 text-white">
                                        <tr>
                                            <th class="py-2 px-3 text-left">Nama</th>
                                            <th class="py-2 px-3 text-center">Qty</th>
                                            <th class="py-2 px-3 text-right">Harga</th>
                                            <th class="py-2 px-3 text-right">Diskon Item</th>
                                            <th class="py-2 px-3 text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-50">
                    `;

                            data.details.forEach(item => {
                                detailsHtml += `
                            <tr class="border-b border-gray-300 hover:bg-gray-100 transition-all duration-200">
                                <td class="py-2 px-3">${item.food_nama}</td>
                                <td class="py-2 px-3 text-center">${item.qty}</td>
                                <td class="py-2 px-3 text-right">Rp ${new Intl.NumberFormat('id-ID').format(item.harga_bandrol)}</td>
                                <td class="py-2 px-3 text-right text-red-500">${item.diskon_persen ? `${item.diskon_persen}% (-Rp ${new Intl.NumberFormat('id-ID').format(item.harga_diskon)})` : '-'}</td>
                                <td class="py-2 px-3 text-right font-bold text-green-700">Rp ${new Intl.NumberFormat('id-ID').format(item.total)}</td>
                            </tr>
                        `;
                            });

                            detailsHtml += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;

                            document.getElementById("transactionDetail").innerHTML =
                                detailsHtml;
                        })
                        .catch(error => console.error("Error fetching details:", error));
                });
            });
        });
    </script>
@endsection
