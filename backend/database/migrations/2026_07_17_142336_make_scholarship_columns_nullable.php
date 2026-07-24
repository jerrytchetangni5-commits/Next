<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            if (!Schema::hasColumn('scholarships', 'university')) {
                $table->string('university')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'domain')) {
                $table->string('domain')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'level')) {
                $table->string('level')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'funding_type')) {
                $table->string('funding_type')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'benefits')) {
                $table->text('benefits')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'requirements')) {
                $table->text('requirements')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'required_documents')) {
                $table->text('required_documents')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('scholarships', 'source')) {
                $table->string('source')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            if (Schema::hasColumn('scholarships', 'university')) $table->dropColumn('university');
            if (Schema::hasColumn('scholarships', 'domain')) $table->dropColumn('domain');
            if (Schema::hasColumn('scholarships', 'level')) $table->dropColumn('level');
            if (Schema::hasColumn('scholarships', 'funding_type')) $table->dropColumn('funding_type');
            if (Schema::hasColumn('scholarships', 'benefits')) $table->dropColumn('benefits');
            if (Schema::hasColumn('scholarships', 'requirements')) $table->dropColumn('requirements');
            if (Schema::hasColumn('scholarships', 'required_documents')) $table->dropColumn('required_documents');
            if (Schema::hasColumn('scholarships', 'image')) $table->dropColumn('image');
            if (Schema::hasColumn('scholarships', 'source')) $table->dropColumn('source');
        });
    }
};