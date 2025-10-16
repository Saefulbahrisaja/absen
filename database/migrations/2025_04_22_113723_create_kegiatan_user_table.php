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
        Schema::create('kegiatan_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id') ->constrained('user')->onDelete('cascade');
            $table->date('tanggal_penugasan')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('nama_kegiatan');
            $table->enum('status_kegiatan', ['Aktif', 'Selesai'])->nullable();
            $table->integer('poin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_user');
    }
};
