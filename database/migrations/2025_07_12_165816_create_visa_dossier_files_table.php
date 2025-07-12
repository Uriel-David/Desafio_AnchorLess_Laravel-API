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
        Schema::create('visa_dossier_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ext');
            $table->string('path');
            $table->string('url');
            $table->string('type');
            $table->string('tag');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_dossier_files');
    }
};
