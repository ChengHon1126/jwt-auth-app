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
        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->id();                                      // 主鍵
            $table->foreignId('user_id')                       // 上傳教師的用戶ID
                ->constrained()
                ->onDelete('cascade');                       // 當用戶刪除時連同教案一起刪除
            $table->string('title');                           // 教案標題
            $table->text('description')                        // 教案描述
                ->nullable();
            $table->string('file_path');                       // 教案PDF檔案路徑
            $table->string('grade_level');                     // 適用年級：elementary(國小), junior_high(國中), senior_high(高中)
            $table->text('teaching_goals')                     // 教學目標
                ->nullable();
            $table->text('activities')                         // 課堂活動建議
                ->nullable();
            $table->boolean('is_approved')                     // 是否發布
                ->default(false);
            $table->timestamps();                              // 建立和更新時間
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_plans');
    }
};
