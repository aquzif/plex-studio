<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->boolean('auto_download')->default(false);
            $table->string('package_name')->nullable();
            $table->string('auto_download_status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->dropColumn('auto_download');
            $table->dropColumn('package_name');
            $table->dropColumn('auto_download_status');
        });
    }
};
