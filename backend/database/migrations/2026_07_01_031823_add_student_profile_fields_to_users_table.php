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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nationality')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('study_level')->nullable();
            $table->string('study_domain')->nullable();
            $table->decimal('average', 5, 2)->nullable();
            $table->json('languages')->nullable();
            $table->enum('english_level', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])->nullable();
            $table->json('skills')->nullable();
            $table->json('experiences')->nullable();
            $table->json('interests')->nullable();
            $table->json('destination_countries')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nationality',
                'birth_date',
                'gender',
                'study_level',
                'study_domain',
                'average',
                'languages',
                'english_level',
                'skills',
                'experiences',
                'interests',
                'destination_countries'
            ]);
        });
    }
};
