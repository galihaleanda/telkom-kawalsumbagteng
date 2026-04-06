<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Sto;
use App\Models\Sektor;
use App\Services\MasterData\Sto\StoService;
use Illuminate\Http\Request;

class StoController extends Controller
{
    public function __construct(protected StoService $stoService) {}

    public function index()
    {
        $stos = Sto::with('sektor.serviceArea')->latest()->get();
        return view('master-data.stos.index', compact('stos'));
    }

    public function create()
    {
        $sektors = Sektor::with('serviceArea')->get();
        return view('master-data.stos.create', compact('sektors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'nullable|string|max:255',
            'sektor_id' => 'required|exists:sektors,id'
        ]);

        $this->stoService->store($data);
        return redirect()->route('stos.index')->with('success', 'STO berhasil ditambahkan!');
    }

    public function show(Sto $sto)
    {
        $sto->load('sektor');
        return view('master-data.stos.show', compact('sto'));
    }

    public function edit(Sto $sto)
    {
        $sektors = Sektor::with('serviceArea')->get();
        return view('master-data.stos.edit', compact('sto', 'sektors'));
    }

    public function update(Request $request, Sto $sto)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'nullable|string|max:255',
            'sektor_id' => 'required|exists:sektors,id'
        ]);

        $this->stoService->update($sto->id, $data);
        return redirect()->route('stos.index')->with('success', 'STO berhasil diperbarui!');
    }

    public function destroy(Sto $sto)
    {
        $this->stoService->delete($sto->id);
        return redirect()->route('stos.index')->with('success', 'STO berhasil dihapus!');
    }
}
