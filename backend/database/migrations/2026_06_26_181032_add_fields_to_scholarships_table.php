<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('level')->nullable();
            $table->enum('funding_type', ['full', 'partial', 'unfunded'])->nullable();
            $table->string('amount')->nullable();
            $table->string('currency')->nullable();
            $table->integer('days_remaining')->nullable();
            $table->decimal('min_average', 4, 2)->nullable();
            $table->string('required_english_level')->nullable();
            $table->json('languages')->nullable();
            $table->string('source')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn([
                'funding_type',
                'amount',
                'currency',
                'days_remaining',
                'min_average',
                'required_english_level',
                'languages',
                'source'
            ]);
        });
    }
};