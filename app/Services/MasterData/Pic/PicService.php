<?php

namespace App\Services\MasterData\Pic;

use App\Models\Pic;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Exception;

class PicService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $data['name'] = trim($data['name']);
                
                if (Pic::where('name', $data['name'])->where('service_area_id', $data['service_area_id'])->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'PIC dengan nama ini sudah terdaftar di Service Area tersebut.'
                    ]);
                }

                return Pic::create($data);
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal menyimpan data PIC: ' . $e->getMessage());
            }
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $pic = Pic::findOrFail($id);
                $data['name'] = trim($data['name']);
                $saId = $data['service_area_id'] ?? $pic->service_area_id;
                
                if (Pic::where('name', $data['name'])
                       ->where('service_area_id', $saId)
                       ->where('id', '!=', $id)
                       ->exists()) {
                    throw ValidationException::withMessages([
                        'name' => 'PIC dengan nama ini sudah terdaftar di Service Area tersebut.'
                    ]);
                }

                $pic->update($data);
                return $pic;
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal mengupdate data PIC: ' . $e->getMessage());
            }
        });
    }

    public function delete(int $id)
    {
        // Ujung tombak, bebas hapus
        $pic = Pic::findOrFail($id);
        return DB::transaction(fn () => $pic->delete());
    }
}
