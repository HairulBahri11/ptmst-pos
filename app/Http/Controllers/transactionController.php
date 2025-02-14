<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class transactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Ambil data transaksi dengan relasi customer dan detail transaction
            $transactions = Transaction::with(['customer', 'details.food'])->latest()->get();
            return view('transaction.index', compact('transactions'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data transaksi.');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::with('customer', 'details')->find($id);

        return response()->json([
            'no_transaksi' => $transaction->no_transaksi,
            'tanggal' => $transaction->tanggal,
            'subtotal' => $transaction->subtotal,
            'diskon' => $transaction->diskon,
            'ongkir' => $transaction->ongkir,
            'total_bayar' => $transaction->total_bayar,
            'customer' => $transaction->customer->name ?? 'Tidak Diketahui',
            'details' => $transaction->details->map(function ($detail) {
                return [
                    'food_nama' => $detail->food->nama,
                    'qty' => $detail->qty,
                    'harga_bandrol' => $detail->harga_bandrol,
                    'harga_diskon' => $detail->harga_diskon,
                    'diskon_persen' => $detail->diskon_persen,
                    'total' => $detail->total,
                ];
            }),
        ]);
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
