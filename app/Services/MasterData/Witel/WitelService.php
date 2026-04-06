<?php

namespace App\Services\MasterData\Witel;

use App\Models\Witel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Exception;

class WitelService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $data['name'] = trim($data['name']);
                
                return Witel::create($data);
            } catch (Exception $e) {
                throw new RuntimeException('Gagal menyimpan data Witel: ' . $e->getMessage());
            }
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $witel = Witel::findOrFail($id);
                $data['name'] = trim($data['name']);
                
                $witel->update($data);
                return $witel;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal mengupdate data Witel: ' . $e->getMessage());
            }
        });
    }

    public function delete(int $id)
    {
        $witel = Witel::findOrFail($id);

        if ($witel->branches()->exists()) {
            throw ValidationException::withMessages([
                'error' => 'Tidak dapat menghapus Witel karena masih memiliki data Branch terkait.'
            ]);
        }

        return DB::transaction(fn () => $witel->delete());
    }
}