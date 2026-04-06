<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Witel;
use App\Services\MasterData\Branch\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(protected BranchService $branchService) {}

    public function index()
    {
        $branches = Branch::with('witel')->latest()->get();
        return view('master-data.branches.index', compact('branches'));
    }

    public function create()
    {
        $witels = Witel::all(); // Untuk dropdown pilihan Witel
        return view('master-data.branches.create', compact('witels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'witel_id' => 'required|exists:witels,id'
        ]);

        $this->branchService->store($data);
        return redirect()->route('branches.index')->with('success', 'Branch berhasil ditambahkan!');
    }

    public function show(Branch $branch)
    {
        $branch->load(['witel', 'datels']);
        return view('master-data.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $witels = Witel::all();
        return view('master-data.branches.edit', compact('branch', 'witels'));
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'witel_id' => 'required|exists:witels,id'
        ]);

        $this->branchService->update($branch->id, $data);
        return redirect()->route('branches.index')->with('success', 'Branch berhasil diperbarui!');
    }

    public function destroy(Branch $branch)
    {
        $this->branchService->delete($branch->id);
        return redirect()->route('branches.index')->with('success', 'Branch berhasil dihapus!');
    }
}
