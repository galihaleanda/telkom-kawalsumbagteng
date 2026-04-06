<?php

namespace App\Services\MasterData\Sektor;

use App\Models\Sektor;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Exception;

class SektorService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $data['name'] = trim($data['name']);
                
                if (Sektor::where('name', $data['name'])->where('service_area_id', $data['service_area_id'])->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'Nama Sektor ini sudah ada di Service Area tersebut.'
                    ]);
                }

                return Sektor::create($data);
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal menyimpan data Sektor: ' . $e->getMessage());
            }
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $sektor = Sektor::findOrFail($id);
                $data['name'] = trim($data['name']);
                $saId = $data['service_area_id'] ?? $sektor->service_area_id;
                
                if (Sektor::where('name', $data['name'])
                          ->where('service_area_id', $saId)
                          ->where('id', '!=', $id)
                          ->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'Nama Sektor ini sudah ada di Service Area tersebut.'
                    ]);
                }

                $sektor->update($data);
                return $sektor;
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal mengupdate data Sektor: ' . $e->getMessage());
            }
        });
    }

    public function delete(int $id)
    {
        $sektor = Sektor::findOrFail($id);

        if ($sektor->stos()->exists()) {
            throw ValidationException::withMessages([
                'error' => 'Tidak dapat menghapus Sektor karena masih memiliki data STO terkait.'
            ]);
        }

        return DB::transaction(fn () => $sektor->delete());
    }
}
