<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('university')->nullable()->change();
            $table->string('domain')->nullable()->change();
            $table->string('level')->nullable()->change();
            $table->string('funding_type')->nullable()->change();
            $table->text('benefits')->nullable()->change();
            $table->text('requirements')->nullable()->change();
            $table->text('required_documents')->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->string('source')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('university')->nullable(false)->change();
            $table->string('domain')->nullable(false)->change();
            $table->string('level')->nullable(false)->change();
            $table->string('funding_type')->nullable(false)->change();
            $table->text('benefits')->nullable(false)->change();
            $table->text('requirements')->nullable(false)->change();
            $table->text('required_documents')->nullable(false)->change();
            $table->string('image')->nullable(false)->change();
            $table->string('source')->nullable(false)->change();
        });
    }
};