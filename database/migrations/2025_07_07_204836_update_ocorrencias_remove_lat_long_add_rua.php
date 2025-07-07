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
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
            $table->string('rua')->nullable()->after('bairro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('bairro');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->dropColumn('rua');
        });
    }
};
