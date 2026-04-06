<?php

namespace App\Services\MasterData\Branch;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Exception;

class BranchService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $data['name'] = trim($data['name']);
                
                if (Branch::where('name', $data['name'])->where('witel_id', $data['witel_id'])->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'Nama Branch ini sudah ada di Witel tersebut.'
                    ]);
                }

                return Branch::create($data);
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal menyimpan data Branch: ' . $e->getMessage());
            }
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $branch = Branch::findOrFail($id);
                $data['name'] = trim($data['name']);
                $witelId = $data['witel_id'] ?? $branch->witel_id;
                
                if (Branch::where('name', $data['name'])
                          ->where('witel_id', $witelId)
                          ->where('id', '!=', $id)
                          ->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'Nama Branch ini sudah ada di Witel tersebut.'
                    ]);
                }

                $branch->update($data);
                return $branch;
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal mengupdate data Branch: ' . $e->getMessage());
            }
        });
    }

    public function delete(int $id)
    {
        $branch = Branch::findOrFail($id);

        if ($branch->datels()->exists()) {
            throw ValidationException::withMessages([
                'error' => 'Tidak dapat menghapus Branch karena masih memiliki data Datel terkait.'
            ]);
        }

        return DB::transaction(fn () => $branch->delete());
    }
}