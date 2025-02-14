<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Customer;
use App\Models\Detail_Transaction;
use App\Models\Transaction;
use Illuminate\Http\Request;

class posController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $foods = Food::where('status', 1)->get();
            $customers = Customer::all();
            return view('pos.index', compact('foods', 'customers'));
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            // Debugging: Log data request
            \Log::info('Request Data:', $request->all());

            // Validasi data input
            $request->validate([
                'customer_id' => 'required',
                'tanggal' => 'required|date',
                'total_bayar' => 'required|numeric',
                'diskon_total' => 'nullable|numeric',
                'biaya_ongkir' => 'nullable|numeric',
                'items' => 'required|array',
                'items.*.id' => 'required',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.harga' => 'required|numeric',
                'items.*.diskon' => 'nullable|numeric',
                'items.*.harga_diskon' => 'nullable|numeric',
                'items.*.total' => 'required|numeric',
            ]);

            // Generate Kode Transaksi: TRX-YYYYMMDD-RANDOM
            $randomNumber = mt_rand(100000, 999999);
            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . $randomNumber;

            // Hitung subtotal dari total semua item
            $subtotal = collect($request->items)->sum('total');

            // Ambil diskon transaksi & biaya ongkir dari request, default ke 0 jika tidak dikirim
            $diskonTotal = $request->diskon_total ?? 0;
            $biayaOngkir = $request->biaya_ongkir ?? 0;

            // Hitung total bayar setelah diskon transaksi & biaya ongkir
            $totalBayar = $subtotal - $diskonTotal + $biayaOngkir;

            // Simpan transaksi utama
            $transaction = Transaction::create([
                'no_transaksi' => $kodeTransaksi,
                'customer_id' => $request->customer_id,
                'tanggal' => $request->tanggal,
                'subtotal' => $subtotal,
                'diskon' => $diskonTotal,
                'ongkir' => $biayaOngkir,
                'total_bayar' => $totalBayar,
                'status' => 1, // Default status aktif
            ]);

            // Simpan detail transaksi
            foreach ($request->items as $item) {
                Detail_Transaction::create([
                    'transaksi_id' => $transaction->id,
                    'food_id' => $item['id'],
                    'qty' => $item['qty'],
                    'harga_bandrol' => $item['harga'],
                    'diskon_persen' => $item['diskon'] ?? 0,
                    'harga_diskon' => $item['harga_diskon'] ?? 0,
                    'total' => $item['total'],
                ]);
            }

            return response()->json([
                'message' => 'Transaksi berhasil disimpan',
                'kode_transaksi' => $kodeTransaksi,
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Transaction Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan pada server',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
