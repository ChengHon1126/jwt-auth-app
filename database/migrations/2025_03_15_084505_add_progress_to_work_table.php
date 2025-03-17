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
        Schema::table('works', function (Blueprint $table) {
            $table->enum('progress', [
                'draft',     // 1. 暂存 
                'pending',   // 2. 审核中
                'approved',  // 3. 审核通过
                'rejected'   // 4. 审核不通过
            ])->default('pending')->after('like')->comment('作品進度狀態');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            //
        });
    }
};
