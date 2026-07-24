<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('scholarships', function (Blueprint $table) {
            // Vérifie si la colonne n'existe pas avant de l'ajouter
            if (!Schema::hasColumn('scholarships', 'apply_link')) {
                $table->text('apply_link')->nullable()->after('link');
            }
            if (!Schema::hasColumn('scholarships', 'official_website')) {
                $table->text('official_website')->nullable()->after('apply_link');
            }
            if (!Schema::hasColumn('scholarships', 'details')) {
                $table->text('details')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn(['apply_link', 'official_website', 'details']);
        });
    }
};

