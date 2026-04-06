<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Witel;
use App\Services\MasterData\Witel\WitelService;
use Illuminate\Http\Request;

class WitelController extends Controller
{
    public function __construct(protected WitelService $witelService) {}

    public function index()
    {
        $witels = Witel::latest()->get();
        return view('master-data.witels.index', compact('witels'));
    }

    public function create()
    {
        return view('master-data.witels.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $this->witelService->store($data);
        
        return redirect()->route('witels.index')->with('success', 'Witel berhasil ditambahkan!');
    }

    public function show(Witel $witel)
    {
        $witel->load('branches');
        return view('master-data.witels.show', compact('witel'));
    }

    public function edit(Witel $witel)
    {
        return view('master-data.witels.edit', compact('witel'));
    }

    public function update(Request $request, Witel $witel)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $this->witelService->update($witel->id, $data);
        
        return redirect()->route('witels.index')->with('success', 'Witel berhasil diperbarui!');
    }

    public function destroy(Witel $witel)
    {
        $this->witelService->delete($witel->id);
        return redirect()->route('witels.index')->with('success', 'Witel berhasil dihapus!');
    }
}