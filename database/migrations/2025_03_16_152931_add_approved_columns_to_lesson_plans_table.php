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
        Schema::table('lesson_plans', function (Blueprint $table) {
            $table->foreignId('approved_user_id')->nullable()->constrained('users')->onDelete('set null')->after('is_approved')->comment('審核人員'); // 審核人的用戶ID
            $table->timestamp('approved_at')->nullable()->after('approved_user_id')->comment('審核時間'); // 審核通過時間
            $table->boolean('is_delete')->default(false)->after('approved_at')->comment('是否刪除'); // 是否被刪除
            $table->foreignId('deleted_user_id')->nullable()->constrained('users')->onDelete('set null')->after('is_delete')->comment('刪除人員'); // 審核人的用戶ID
            $table->timestamp('deleted_at')->nullable()->after('deleted_user_id')->comment('刪除時間'); // 刪除時間
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_plans', function (Blueprint $table) {
            $table->dropColumn('approved_at');
            $table->dropForeign(['approved_user_id']);
            $table->dropColumn('is_delete');
            $table->dropColumn('deleted_user_id');
            $table->dropColumn('deleted_at');
        });
    }
};
