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
        Schema::create('lesson_plan_grades', function (Blueprint $table) {
            $table->id();                                      // 主鍵
            $table->foreignId('lesson_plan_id')                // 關聯的教案ID
                ->constrained()
                ->onDelete('cascade');                       // 當教案刪除時連同標籤一起刪除
            $table->string('grade_level');                     // 年級標籤: elementary(國小), junior_high(國中), senior_high(高中)
            $table->timestamps();                              // 建立和更新時間
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_plan_grades');
    }
};
