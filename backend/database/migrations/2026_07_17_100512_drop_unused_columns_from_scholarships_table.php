<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn([
                'amount',
                'currency',
                'min_average',
                'required_english_level',
                'languages',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('amount')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('min_average', 4, 2)->nullable();
            $table->string('required_english_level')->nullable();
            $table->json('languages')->nullable();
        });
    }
};
