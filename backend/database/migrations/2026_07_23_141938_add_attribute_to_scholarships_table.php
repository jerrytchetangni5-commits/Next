<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            if (!Schema::hasColumn('scholarships', 'apply_link')) {
                $table->text('apply_link')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'official_website')) {
                $table->text('official_website')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'details')) {
                $table->text('details')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            if (Schema::hasColumn('scholarships', 'apply_link')) {
                $table->dropColumn('apply_link');
            }
            if (Schema::hasColumn('scholarships', 'official_website')) {
                $table->dropColumn('official_website');
            }
            if (Schema::hasColumn('scholarships', 'details')) {
                $table->dropColumn('details');
            }
        });
    }
};