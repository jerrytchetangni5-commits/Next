<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'phone_number')) {
                $table->integer('phone_number');
            }
        });
    }
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // CORRECTION : On supprime la colonne, on ne la recrée pas !
            if (Schema::hasColumn('contacts', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
        });
    }
};