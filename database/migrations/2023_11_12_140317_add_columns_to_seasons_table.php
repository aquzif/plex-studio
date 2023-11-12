<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->string('quality')->default('undef');
            $table->boolean('needs_update')->default(false);
            $table->string('audio_languages')->default('[]');
            $table->string('subtitle_languages')->default('[]');
        });
    }

    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropColumn('quality');
            $table->dropColumn('needs_update');
            $table->dropColumn('audio_languages');
            $table->dropColumn('subtitle_languages');
        });
    }
};
