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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('default_post_thumbnail')->nullable()->after('site_favicon');
            $table->string('site_logo_mobile')->nullable()->after('site_logo_dark');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('default_post_thumbnail');
            $table->dropColumn('site_logo_mobile');
        });
    }
};
