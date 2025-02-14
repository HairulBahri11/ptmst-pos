<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class customerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customers = Customer::all();
            return view('customer.index', compact('customers'));
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
        ]);

        if ($request->id) {
            // Ambil data customer berdasarkan ID
            $customer = Customer::findOrFail($request->id);

            // Ambil hanya field yang berubah
            $updatedFields = [];
            if ($customer->nama !== $request->nama) {
                $updatedFields['name'] = $request->nama;
            }
            if ($customer->alamat !== $request->alamat) {
                $updatedFields['alamat'] = $request->alamat;
            }
            if ($customer->no_telp !== $request->no_telp) {
                $updatedFields['telp'] = $request->no_telp;
            }

            // Perbarui jika ada perubahan
            if (!empty($updatedFields)) {
                $customer->update($updatedFields);
            }
        } else {
            // Jika data baru, buat customer baru
            $kode = 'CUST-' . mt_rand(1000, 9999);
            Customer::create([
                'kode' => $kode,
                'name' => $request->nama,
                'alamat' => $request->alamat,
                'telp' => $request->no_telp,
                'user_id' => 1,
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Data customer berhasil disimpan!');
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

    public function destroy($id)
    {
        // softdelete
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Data customer berhasil dihapus!');
    }
}
