<?php

namespace App\Services\MasterData\ServiceArea;

use App\Models\ServiceArea;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Exception;

class ServiceAreaService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $data['name'] = trim($data['name']);
                if (isset($data['sa_code'])) {
                    $data['sa_code'] = strtoupper(trim($data['sa_code']));
                }
                
                // Cek nama duplikat di Datel yang sama
                if (ServiceArea::where('name', $data['name'])->where('datel_id', $data['datel_id'])->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'Nama Service Area ini sudah ada di Datel tersebut.'
                    ]);
                }

                // Cek sa_code duplikat (asumsi sa_code unik secara global)
                if (isset($data['sa_code']) && ServiceArea::where('sa_code', $data['sa_code'])->exists()) {
                    throw ValidationException::withMessages([
                        'sa_code' => 'Kode Service Area (sa_code) ini sudah digunakan.'
                    ]);
                }

                return ServiceArea::create($data);
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal menyimpan data Service Area: ' . $e->getMessage());
            }
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $serviceArea = ServiceArea::findOrFail($id);
                $data['name'] = trim($data['name']);
                $datelId = $data['datel_id'] ?? $serviceArea->datel_id;

                if (isset($data['sa_code'])) {
                    $data['sa_code'] = strtoupper(trim($data['sa_code']));
                }
                
                // Cek duplikat nama
                if (ServiceArea::where('name', $data['name'])
                               ->where('datel_id', $datelId)
                               ->where('id', '!=', $id)
                               ->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'Nama Service Area ini sudah ada di Datel tersebut.'
                    ]);
                }

                // Cek duplikat sa_code
                if (isset($data['sa_code']) && ServiceArea::where('sa_code', $data['sa_code'])->where('id', '!=', $id)->exists()) {
                    throw ValidationException::withMessages([
                        'sa_code' => 'Kode Service Area (sa_code) ini sudah digunakan.'
                    ]);
                }

                $serviceArea->update($data);
                return $serviceArea;
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal mengupdate data Service Area: ' . $e->getMessage());
            }
        });
    }

    public function delete(int $id)
    {
        $serviceArea = ServiceArea::findOrFail($id);

        if ($serviceArea->sektors()->exists() || $serviceArea->pics()->exists()) {
            throw ValidationException::withMessages([
                'error' => 'Tidak dapat menghapus Service Area karena masih memiliki data Sektor atau PIC terkait.'
            ]);
        }

        return DB::transaction(fn () => $serviceArea->delete());
    }
}
