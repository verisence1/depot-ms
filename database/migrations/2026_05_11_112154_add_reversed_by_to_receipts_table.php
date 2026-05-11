<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receipts', function (Blueprint $table) {

            $table->foreignId('reversed_by')
                ->nullable()
                ->after('reversed_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {

            $table->dropForeign(['reversed_by']);

            $table->dropColumn('reversed_by');
        });
    }
};
