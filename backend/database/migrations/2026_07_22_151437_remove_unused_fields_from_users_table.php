<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'average',
                'languages',
                'english_level',
                'skills',
                'experiences',
                'interests',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('average', 4, 2)->nullable();
            $table->json('languages')->nullable();
            $table->string('english_level')->nullable();
            $table->json('skills')->nullable();
            $table->json('experiences')->nullable();
            $table->json('interests')->nullable();
        });
    }
};