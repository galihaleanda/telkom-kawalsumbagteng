<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stos', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // contoh: ARK
            $table->string('name'); // contoh: Arengka
            $table->foreignId('sektor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['code', 'sektor_id']); // biar tidak duplicate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stos');
    }
};
