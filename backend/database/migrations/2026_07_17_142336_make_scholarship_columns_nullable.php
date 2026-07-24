<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('university')->nullable();
            $table->string('domain')->nullable();
            $table->string('level')->nullable();
            $table->string('funding_type')->nullable();
            $table->text('benefits')->nullable();
            $table->text('requirements')->nullable();
            $table->text('required_documents')->nullable();
            $table->string('image')->nullable();
            $table->string('source')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('university')->nullable(false);
            $table->string('domain')->nullable(false);
            $table->string('level')->nullable(false);
            $table->string('funding_type')->nullable(false);
            $table->text('benefits')->nullable(false);
            $table->text('requirements')->nullable(false);
            $table->text('required_documents')->nullable(false);
            $table->string('image')->nullable(false);
            $table->string('source')->nullable(false);
        });
    }
};