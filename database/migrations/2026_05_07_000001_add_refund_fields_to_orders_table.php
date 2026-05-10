<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('type')->default('sale')->after('status'); // 'sale' or 'refund'
            $table->foreignId('refund_of_order_id')->nullable()->after('type')->constrained('orders')->nullOnDelete();
            $table->text('refund_reason')->nullable()->after('refund_of_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['refund_of_order_id']);
            $table->dropColumn(['type', 'refund_of_order_id', 'refund_reason']);
        });
    }
};
