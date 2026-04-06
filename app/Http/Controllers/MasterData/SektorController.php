<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Sektor;
use App\Models\ServiceArea;
use App\Services\MasterData\Sektor\SektorService;
use Illuminate\Http\Request;

class SektorController extends Controller
{
    public function __construct(protected SektorService $sektorService) {}

    public function index()
    {
        $sektors = Sektor::with('serviceArea.datel')->latest()->get();
        return view('master-data.sektors.index', compact('sektors'));
    }

    public function create()
    {
        $serviceAreas = ServiceArea::with('datel')->get();
        return view('master-data.sektors.create', compact('serviceAreas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'service_area_id' => 'required|exists:service_areas,id'
        ]);

        $this->sektorService->store($data);
        return redirect()->route('sektors.index')->with('success', 'Sektor berhasil ditambahkan!');
    }

    public function show(Sektor $sektor)
    {
        $sektor->load(['serviceArea', 'stos']);
        return view('master-data.sektors.show', compact('sektor'));
    }

    public function edit(Sektor $sektor)
    {
        $serviceAreas = ServiceArea::with('datel')->get();
        return view('master-data.sektors.edit', compact('sektor', 'serviceAreas'));
    }

    public function update(Request $request, Sektor $sektor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'service_area_id' => 'required|exists:service_areas,id'
        ]);

        $this->sektorService->update($sektor->id, $data);
        return redirect()->route('sektors.index')->with('success', 'Sektor berhasil diperbarui!');
    }

    public function destroy(Sektor $sektor)
    {
        $this->sektorService->delete($sektor->id);
        return redirect()->route('sektors.index')->with('success', 'Sektor berhasil dihapus!');
    }
}