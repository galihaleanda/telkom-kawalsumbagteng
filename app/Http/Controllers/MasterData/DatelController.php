<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Datel;
use App\Models\Branch;
use App\Services\MasterData\Datel\DatelService;
use Illuminate\Http\Request;

class DatelController extends Controller
{
    public function __construct(protected DatelService $datelService) {}

    public function index()
    {
        $datels = Datel::with('branch.witel')->latest()->get();
        return view('master-data.datels.index', compact('datels'));
    }

    public function create()
    {
        $branches = Branch::with('witel')->get();
        return view('master-data.datels.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id'
        ]);

        $this->datelService->store($data);
        return redirect()->route('datels.index')->with('success', 'Datel berhasil ditambahkan!');
    }

    public function show(Datel $datel)
    {
        $datel->load(['branch', 'serviceAreas']);
        return view('master-data.datels.show', compact('datel'));
    }

    public function edit(Datel $datel)
    {
        $branches = Branch::with('witel')->get();
        return view('master-data.datels.edit', compact('datel', 'branches'));
    }

    public function update(Request $request, Datel $datel)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id'
        ]);

        $this->datelService->update($datel->id, $data);
        return redirect()->route('datels.index')->with('success', 'Datel berhasil diperbarui!');
    }

    public function destroy(Datel $datel)
    {
        $this->datelService->delete($datel->id);
        return redirect()->route('datels.index')->with('success', 'Datel berhasil dihapus!');
    }
}