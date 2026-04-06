<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Pic;
use App\Models\ServiceArea;
use App\Services\MasterData\Pic\PicService;
use Illuminate\Http\Request;

class PicController extends Controller
{
    public function __construct(protected PicService $picService) {}

    public function index()
    {
        $pics = Pic::with('serviceArea')->latest()->get();
        return view('master-data.pics.index', compact('pics'));
    }

    public function create()
    {
        $serviceAreas = ServiceArea::with('datel')->get();
        return view('master-data.pics.create', compact('serviceAreas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'service_area_id' => 'required|exists:service_areas,id'
        ]);

        $this->picService->store($data);
        return redirect()->route('pics.index')->with('success', 'PIC berhasil ditambahkan!');
    }

    public function show(Pic $pic)
    {
        $pic->load('serviceArea');
        return view('master-data.pics.show', compact('pic'));
    }

    public function edit(Pic $pic)
    {
        $serviceAreas = ServiceArea::with('datel')->get();
        return view('master-data.pics.edit', compact('pic', 'serviceAreas'));
    }

    public function update(Request $request, Pic $pic)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'service_area_id' => 'required|exists:service_areas,id'
        ]);

        $this->picService->update($pic->id, $data);
        return redirect()->route('pics.index')->with('success', 'PIC berhasil diperbarui!');
    }

    public function destroy(Pic $pic)
    {
        $this->picService->delete($pic->id);
        return redirect()->route('pics.index')->with('success', 'PIC berhasil dihapus!');
    }
}