<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('glare_landings', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('trust_line');
        });

        Schema::table('glare1_landings', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('trust_line');
        });
    }

    public function down(): void
    {
        Schema::table('glare_landings', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
        });

        Schema::table('glare1_landings', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
        });
    }
};
