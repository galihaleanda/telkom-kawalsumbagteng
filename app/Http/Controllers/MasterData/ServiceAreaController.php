<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\ServiceArea;
use App\Models\Datel;
use App\Services\MasterData\ServiceArea\ServiceAreaService;
use Illuminate\Http\Request;

class ServiceAreaController extends Controller
{
    public function __construct(protected ServiceAreaService $serviceAreaService) {}

    public function index()
    {
        $serviceAreas = ServiceArea::with('datel.branch')->latest()->get();
        return view('master-data.service-areas.index', compact('serviceAreas'));
    }

    public function create()
    {
        $datels = Datel::with('branch')->get();
        return view('master-data.service-areas.create', compact('datels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sa_code' => 'nullable|string|max:50',
            'datel_id' => 'required|exists:datels,id'
        ]);

        $this->serviceAreaService->store($data);
        return redirect()->route('service-areas.index')->with('success', 'Service Area berhasil ditambahkan!');
    }

    public function show(ServiceArea $serviceArea)
    {
        $serviceArea->load(['datel', 'sektors', 'pics']);
        return view('master-data.service-areas.show', compact('serviceArea'));
    }

    public function edit(ServiceArea $serviceArea)
    {
        $datels = Datel::with('branch')->get();
        return view('master-data.service-areas.edit', compact('serviceArea', 'datels'));
    }

    public function update(Request $request, ServiceArea $serviceArea)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sa_code' => 'nullable|string|max:50',
            'datel_id' => 'required|exists:datels,id'
        ]);

        $this->serviceAreaService->update($serviceArea->id, $data);
        return redirect()->route('service-areas.index')->with('success', 'Service Area berhasil diperbarui!');
    }

    public function destroy(ServiceArea $serviceArea)
    {
        $this->serviceAreaService->delete($serviceArea->id);
        return redirect()->route('service-areas.index')->with('success', 'Service Area berhasil dihapus!');
    }
}