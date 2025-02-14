@extends('app')
@section('title', 'POS')
@section('active', 'active')
@section('content')
    <div class="mx-auto flex flex-col lg:flex-row gap-5">
        <!-- Bagian Kiri: Daftar Makanan -->
        <div class="w-full h-full lg:w-8/12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            @foreach ($foods as $food)
                <div class="bg-white rounded-lg shadow-md overflow-hidden p-4 flex flex-col">
                    <img src="{{ asset('storage/img/' . $food->image) }}" alt="{{ $food->nama }}"
                        class="w-full h-40 object-cover rounded-md">
                    <h3 class="text-lg font-semibold mt-2 flex-grow">{{ $food->nama }}</h3>
                    <p class="text-blue-600 font-bold mt-2">Rp {{ number_format($food->harga, 0, ',', '.') }}</p>
                    <button class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 mt-3 tambah-btn"
                        data-id="{{ $food->id }}" data-nama="{{ $food->nama }}" data-harga="{{ $food->harga }}">
                        Tambah
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Bagian Kanan: Detail Pesanan -->
        <div class="w-full lg:w-4/12 bg-white p-5 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2 mb-3">
                🛒 Detail Pesanan
            </h2>

            <!-- Pilih Customer -->
            <div class="mb-3">
                <label for="customer" class="block text-gray-700 font-medium mb-1">Pilih Customer:</label>
                <select id="customer"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm bg-white focus:ring-2 focus:ring-green-400 focus:border-green-500 transition">
                    @foreach ($customers as $cust)
                        <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- List Cart Items -->
            <div class="bg-gray-50 p-3 rounded-lg shadow-inner border border-gray-200">
                <ul id="cart-items" class="space-y-3 divide-y divide-gray-300">
                    <li class="text-gray-500 text-sm text-center py-2">Belum ada pesanan</li>
                </ul>
            </div>

            <!-- Diskon Transaksi -->
            <div class="mt-3">
                <label for="diskon-transaksi" class="block text-gray-700 font-medium mb-1">Diskon Transaksi (%):</label>
                <input type="number" id="diskon-transaksi" value="0" min="0" max="100"
                    class="w-full p-2 border border-gray-300 rounded-md text-right">
            </div>

            <!-- Biaya Ongkir -->
            <div class="mt-3">
                <label for="biaya-ongkir" class="block text-gray-700 font-medium mb-1">Biaya Ongkir (Rp):</label>
                <input type="number" id="biaya-ongkir" value="0" min="0"
                    class="w-full p-2 border border-gray-300 rounded-md text-right">
            </div>

            <!-- Total Harga -->
            <div class="flex justify-between items-center font-semibold text-lg mt-4 py-2 border-t border-gray-300">
                <span class="text-gray-800">Total:</span>
                <span id="total-price" class="text-green-600">Rp 0</span>
            </div>

            <!-- Tombol Checkout -->
            <button id="checkout-btn"
                class="w-full bg-green-500 text-white py-3 rounded-lg text-lg font-medium mt-3 transition duration-200 
        hover:bg-green-700 active:scale-95 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                Checkout
            </button>
        </div>




    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cartItems = document.getElementById("cart-items");
            const totalPriceEl = document.getElementById("total-price");
            const tambahButtons = document.querySelectorAll(".tambah-btn");
            const checkoutBtn = document.getElementById("checkout-btn");
            const diskonTransaksiInput = document.getElementById("diskon-transaksi");
            const biayaOngkirInput = document.getElementById("biaya-ongkir");

            window.cart = [];

            function updateCart() {
                cartItems.innerHTML = "";
                let subtotal = 0;

                window.cart.forEach((item, index) => {
                    let hargaSetelahDiskon = item.harga - (item.harga * item.diskon / 100);
                    let finalPrice = hargaSetelahDiskon * item.qty;
                    subtotal += finalPrice;

                    const li = document.createElement("li");
                    li.className = "pb-3 pt-3";
                    li.innerHTML = `
                <div class="flex justify-between items-center">
                    <span class="font-medium">${item.nama}</span>
                    <button class="text-red-500 font-bold hover:text-red-700 remove-btn" data-index="${index}">✖</button>
                </div>
                
                <div class="flex justify-between items-center mt-2">
                    <div class="flex items-center gap-2">
                        <input type="number" value="${item.qty}" min="1" class="w-12 p-1 border text-center qty-input" data-index="${index}">
                        <span class="text-sm">x Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Diskon:</span>
                        <input type="number" value="${item.diskon}" min="0" max="100" class="w-12 p-1 border text-center diskon-input" data-index="${index}">% 
                    </div>
                </div>

                <div class="flex justify-between mt-2 font-bold">
                    <span>Total</span>
                    <span class="item-price text-green-600">Rp ${new Intl.NumberFormat('id-ID').format(finalPrice)}</span>
                </div>
            `;
                    cartItems.appendChild(li);
                });

                let diskonTransaksi = parseInt(diskonTransaksiInput.value) || 0;
                let biayaOngkir = parseInt(biayaOngkirInput.value) || 0;

                let diskonTotal = subtotal * (diskonTransaksi / 100);
                let totalAkhir = subtotal - diskonTotal + biayaOngkir;

                totalPriceEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalAkhir)}`;
                attachEventListeners();
            }

            function attachEventListeners() {
                document.querySelectorAll(".qty-input").forEach(input => {
                    input.addEventListener("input", function() {
                        const index = this.dataset.index;
                        window.cart[index].qty = Math.max(1, parseInt(this.value) || 1);
                        updateCart();
                    });
                });

                document.querySelectorAll(".diskon-input").forEach(input => {
                    input.addEventListener("input", function() {
                        const index = this.dataset.index;
                        window.cart[index].diskon = Math.min(100, Math.max(0, parseInt(this
                            .value) || 0));
                        updateCart();
                    });
                });

                document.querySelectorAll(".remove-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        const index = this.dataset.index;
                        window.cart.splice(index, 1);
                        updateCart();
                    });
                });

                diskonTransaksiInput.addEventListener("input", updateCart);
                biayaOngkirInput.addEventListener("input", updateCart);
            }

            tambahButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const harga = parseInt(this.dataset.harga);
                    const existingItem = window.cart.find(item => item.id === id);
                    if (existingItem) {
                        existingItem.qty += 1;
                    } else {
                        window.cart.push({
                            id,
                            nama,
                            harga,
                            qty: 1,
                            diskon: 0
                        });
                    }
                    updateCart();
                });
            });

            if (checkoutBtn) {
                checkoutBtn.addEventListener("click", async () => {
                    console.log("Checkout diklik!");

                    const customerId = document.getElementById("customer").value;
                    if (!customerId) {
                        Swal.fire("Peringatan!", "Silakan pilih customer terlebih dahulu!", "warning");
                        return;
                    }

                    if (window.cart.length === 0) {
                        Swal.fire("Peringatan!", "Keranjang masih kosong!", "warning");
                        return;
                    }

                    Swal.fire({
                        title: "Konfirmasi Pesanan",
                        text: "Apakah Anda yakin ingin menyelesaikan pesanan ini?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Ya, Checkout!",
                        cancelButtonText: "Batal"
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            let items = window.cart.map(item => {
                                let hargaSetelahDiskon = item.harga - (item.harga *
                                    item.diskon / 100);
                                return {
                                    id: item.id,
                                    qty: item.qty,
                                    harga: item.harga,
                                    diskon: item.diskon,
                                    harga_diskon: hargaSetelahDiskon,
                                    total: hargaSetelahDiskon * item.qty
                                };
                            });

                            const subtotal = items.reduce((sum, item) => sum + item.total,
                                0);
                            const diskonTotal = subtotal * (parseInt(diskonTransaksiInput
                                .value) / 100);
                            const biayaOngkir = parseInt(biayaOngkirInput.value) || 0;
                            const totalBayar = subtotal - diskonTotal + biayaOngkir;

                            const data = {
                                customer_id: customerId,
                                tanggal: new Date().toISOString().split("T")[0],
                                subtotal,
                                diskon_total: diskonTotal,
                                biaya_ongkir: biayaOngkir,
                                total_bayar: totalBayar,
                                items: items
                            };
                            const csrfToken = document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content');
                            try {
                                let response = await fetch("{{ route('pos.store') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": csrfToken
                                    },
                                    body: JSON.stringify(data)
                                });
                                let result = await response.json();

                                if (response.ok) {
                                    Swal.fire("Sukses!", "Pesanan berhasil disimpan!",
                                        "success").then(() => {
                                        window.location
                                            .reload(); // Reload setelah klik OK
                                    });
                                    window.cart = [];
                                    updateCart();
                                } else {
                                    Swal.fire("Error!", result.message ||
                                        "Terjadi kesalahan.", "error");
                                }

                            } catch (error) {
                                Swal.fire("Error!", "Tidak dapat terhubung ke server.",
                                    "error");
                            }
                        }
                    });
                });
            }
        });
    </script>

@endsection
