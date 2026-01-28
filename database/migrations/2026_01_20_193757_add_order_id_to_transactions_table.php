<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('order_id')->unique()->nullable()->after('id');
        });

        // Generate order_id for existing transactions
        DB::table('transactions')->get()->each(function ($transaction) {
            $orderId = $transaction->id . '-' . bin2hex(random_bytes(5));
            DB::table('transactions')->where('id', $transaction->id)->update(['order_id' => $orderId]);
        });

        // Make order_id not nullable after populating
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('order_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
};
