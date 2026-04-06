<?php

namespace App\Services\MasterData\Sto;

use App\Models\Sto;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Exception;

class StoService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $data['code'] = strtoupper(trim($data['code']));
                if (isset($data['name'])) $data['name'] = trim($data['name']);
                
                // Cek keunikan code per sektor (atau global, tergantung rule)
                if (Sto::where('code', $data['code'])->where('sektor_id', $data['sektor_id'])->exists()) {
                    throw ValidationException::withMessages([
                        'code' => 'Kode STO ini sudah digunakan di Sektor tersebut.'
                    ]);
                }

                return Sto::create($data);
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal menyimpan data STO: ' . $e->getMessage());
            }
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $sto = Sto::findOrFail($id);
                
                if (isset($data['code'])) {
                    $data['code'] = strtoupper(trim($data['code']));
                }
                if (isset($data['name'])) {
                    $data['name'] = trim($data['name']);
                }
                
                $sektorId = $data['sektor_id'] ?? $sto->sektor_id;
                $newCode = $data['code'] ?? $sto->code;
                
                if (Sto::where('code', $newCode)
                       ->where('sektor_id', $sektorId)
                       ->where('id', '!=', $id)
                       ->exists()) {
                    throw ValidationException::withMessages([
                        'code' => 'Kode STO ini sudah digunakan di Sektor tersebut.'
                    ]);
                }

                $sto->update($data);
                return $sto;
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new RuntimeException('Gagal mengupdate data STO: ' . $e->getMessage());
            }
        });
    }

    public function delete(int $id)
    {
        // Ujung tombak, bebas hapus
        $sto = Sto::findOrFail($id);
        return DB::transaction(fn () => $sto->delete());
    }
}