<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class foodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::all();
        return view('food.index', compact('foods'));
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
            'harga' => 'required|numeric',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->id) {
            // Mode Edit
            $food = Food::findOrFail($request->id);
        } else {
            // Mode Tambah
            $food = new Food();
            $kode = 'JFK' . rand(1000, 9999);
            $food->kode = $kode;
        }

        // Simpan data makanan
        $food->nama = $request->nama;
        $food->harga = $request->harga;
        $food->status = $request->status;

        // Handle upload gambar jika ada
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/img', $filename);
            $food->image = $filename;
        }

        $food->save();

        return redirect()->route('foods.index')->with('success', 'Data makanan berhasil disimpan.');
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
