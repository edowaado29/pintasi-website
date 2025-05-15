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
        Schema::create('ibus', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password'); 
            $table->string('fcm_token')->nullable();
            $table->string('nik')->unique()->nullable(); 
            $table->string('nama_ibu');
            $table->string('tempat_lahir')->nullable(); 
            $table->date('tanggal_lahir')->nullable(); 
            $table->string('alamat')->nullable(); 
            $table->string('telepon')->nullable(); 
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibus');
    }
};
