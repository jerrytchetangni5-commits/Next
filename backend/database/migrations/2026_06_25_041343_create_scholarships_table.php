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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('title');               
            $table->string('country');        
            $table->string('university');
            $table->string('domain');
            $table->date('deadline');
            $table->text('description');
            $table->text('benefits')->nullable(); //nullable = peut etre vide
            $table->text('requirements')->nullable();
            $table->string('image')->nullable();
            $table->string('link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
