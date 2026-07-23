<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->text('apply_link')->nullable()->after('link');
            $table->text('official_website')->nullable()->after('apply_link');
            $table->text('details')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn(['apply_link', 'official_website', 'eligibility']);
        });
    }
};