<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyLikeColumnInWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 使用原生 SQL 語句修改欄位
        DB::statement('ALTER TABLE works MODIFY `like` TINYINT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 恢復欄位為非 NULL
        DB::statement('ALTER TABLE works MODIFY `like` TINYINT NOT NULL');
    }
}
