<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('level')->nullable()->after('domain');
            $table->enum('funding_type', ['full', 'partial', 'unfunded'])->nullable()->after('description');
            $table->string('amount')->nullable()->after('funding_type');
            $table->string('currency')->nullable()->after('amount');
            $table->integer('days_remaining')->nullable()->after('benefits');
            $table->decimal('min_average', 4, 2)->nullable()->after('requirements');
            $table->string('required_english_level')->nullable()->after('min_average');
            $table->json('languages')->nullable()->after('required_english_level');
            $table->string('source')->nullable()->after('link');
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