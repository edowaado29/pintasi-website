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
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bayi')->constrained('bayis')->onDelete('cascade');
            $table->float('bb');
            $table->float('tb');
            $table->float('lk');
            $table->float('imt');
            $table->date('tgl_periksa');
            $table->string('status_gizi');
            $table->string('kalori');
            $table->string('protein');
            $table->string('lemak');
            $table->string('karbo');
            $table->string('serat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
