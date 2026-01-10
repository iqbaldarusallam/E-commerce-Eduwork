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
        Schema::table('transactions', function (Blueprint $table) {
            if(!Schema::hasColumn('transactions', 'message')) {
              $table->string('message')->nullable()->after('shipping_fee');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if(Schema::hasColumn('transactions', 'message')) {
              $table->dropColumn('message');
            }
        });
    }
};
