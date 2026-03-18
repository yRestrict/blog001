<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uploaded_by');
            $table->string('original_name');   // nome original do arquivo
            $table->string('stored_name');     // nome salvo no disco (uuid + ext)
            $table->string('path');            // caminho relativo em storage/app/public/media/
            $table->string('mime_type');
            $table->string('extension', 20);
            $table->unsignedBigInteger('size'); // bytes
            $table->unsignedBigInteger('downloads')->default(0);
            $table->timestamps();

            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};