<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('dashboards.services.index', compact('services'));
    }

    public function detail($id)
    {
        $service = Service::findOrFail($id);
        return view('dashboards.services.detail', compact('service'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string',
            'price' => 'required|string',
            'description' => 'required|string',
            'is_home_service' => 'required|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Simpan ke storage/app/public/images/services
            $image->storeAs('images/services', $imageName, 'public');

            // Simpan nama file ke database
            $validated['image'] = $imageName;
        }

        $validated['price'] = preg_replace('/[^\d]/', '', $validated['price']);
        
        
       
        

        Service::create($validated);

        return redirect()->route('dashboard.services.index')
                        ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'service_name' => 'required|string',
            'price' => 'required|string',
            'description' => 'required|string',
            'is_home_service' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $service = Service::findOrFail($id);

        // Cek apakah ada file gambar baru
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Upload gambar baru
            $image->storeAs('images/services', $imageName, 'public');

            // Hapus gambar lama jika ada
            if ($service->image) {
                Storage::disk('public')->delete('images/services/' . $service->image);
            }

            $validated['image'] = $imageName;
        } else {
            // Tetap pakai gambar lama
            $validated['image'] = $service->image;
        }
        $validated['price'] = preg_replace('/[^\d]/', '', $validated['price']);
        // Update service
        $service->update($validated);

        return redirect()->route('dashboard.services.index')->with('success', 'Layanan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        // Hapus gambar dari storage
        if ($service->image) {
            Storage::disk('public')->delete('images/services/' . $service->image);
        }
        // Hapus service dari database
        $service->delete();

        return redirect()->route('dashboard.services.index')->with('success', 'Layanan berhasil dihapus.');
    }

}
