<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receipts', function (Blueprint $table) {

            $table->dropColumn('is_reversed');
        });

        Schema::table('dispatches', function (Blueprint $table) {

            $table->dropColumn('is_cancelled');
        });
    }

    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {

            $table->boolean('is_reversed')
                ->default(false);
        });

        Schema::table('dispatches', function (Blueprint $table) {

            $table->boolean('is_cancelled')
                ->default(false);
        });
    }
};
