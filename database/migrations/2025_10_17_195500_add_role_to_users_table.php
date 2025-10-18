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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 32)->default('user')->after('password');
            $table->string('telegram_chat_id', 64)->nullable()->after('role');
            $table->string('telegram_verification_token', 64)->nullable()->after('telegram_chat_id');
            $table->boolean('telegram_notifications_enabled')->default(false)->after('telegram_verification_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'telegram_chat_id',
                'telegram_verification_token',
                'telegram_notifications_enabled',
            ]);
        });
    }
};
