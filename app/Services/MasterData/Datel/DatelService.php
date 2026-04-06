<?php

namespace App\Services\MasterData\Datel;

use App\Models\Datel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Exception;

class DatelService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $data['name'] = trim($data['name']);
                
                if (Datel::where('name', $data['name'])->where('branch_id', $data['branch_id'])->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'Nama Datel ini sudah ada di Branch tersebut.'
                    ]);
                }

                return Datel::create($data);
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal menyimpan data Datel: ' . $e->getMessage());
            }
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $datel = Datel::findOrFail($id);
                $data['name'] = trim($data['name']);
                $branchId = $data['branch_id'] ?? $datel->branch_id;
                
                if (Datel::where('name', $data['name'])
                         ->where('branch_id', $branchId)
                         ->where('id', '!=', $id)
                         ->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'Nama Datel ini sudah ada di Branch tersebut.'
                    ]);
                }

                $datel->update($data);
                return $datel;
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal mengupdate data Datel: ' . $e->getMessage());
            }
        });
    }

    public function delete(int $id)
    {
        $datel = Datel::findOrFail($id);

        if ($datel->serviceAreas()->exists()) {
            throw ValidationException::withMessages([
                'error' => 'Tidak dapat menghapus Datel karena masih memiliki data Service Area terkait.'
            ]);
        }

        return DB::transaction(fn () => $datel->delete());
    }
}
