<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('be_beca_beneficiarios', function (Blueprint $table) {
            if (!Schema::hasColumn('be_beca_beneficiarios', 'motivo_suspension')) {
                $table->string('motivo_suspension')->nullable()->after('estado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('be_beca_beneficiarios', function (Blueprint $table) {
            if (Schema::hasColumn('be_beca_beneficiarios', 'motivo_suspension')) {
                $table->dropColumn('motivo_suspension');
            }
        });
    }
};
