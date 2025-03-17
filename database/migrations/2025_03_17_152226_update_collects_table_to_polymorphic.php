<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 由於已經手動刪除索引，我們可以直接進行欄位操作
        Schema::table('collects', function (Blueprint $table) {
            // 檢查外鍵是否存在，如果存在則刪除
            if ($this->hasForeignKey('collects', 'work_id')) {
                // 先取得外鍵名稱
                $foreignKeys = $this->getForeignKeys('collects');
                $workForeignKey = null;

                // 尋找與 work_id 相關的外鍵約束
                foreach ($foreignKeys as $foreignKey) {
                    if (str_contains($foreignKey, 'work_id')) {
                        $workForeignKey = $foreignKey;
                        break;
                    }
                }

                // 如果找到了外鍵，就依照名稱刪除它
                if ($workForeignKey) {
                    $table->dropForeign($workForeignKey);
                }
            }

            // 新增 collectable_type 欄位
            $table->string('collectable_type')->after('work_id')->default('works');

            // 重命名 work_id 欄位為 collectable_id
            $table->renameColumn('work_id', 'collectable_id');

            // 為多態關聯添加複合索引
            $table->index(['collectable_id', 'collectable_type']);

            // 添加新的唯一約束
            $table->unique(['user_id', 'collectable_id', 'collectable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 先移除索引和約束
        // Schema::table('collects', function (Blueprint $table) {
        //     $table->dropIndex(['collectable_id', 'collectable_type']);
        //     $table->dropUnique(['user_id', 'collectable_id', 'collectable_type']);
        // });

        // 再重命名欄位
        // Schema::table('collects', function (Blueprint $table) {
        //     $table->renameColumn('collectable_id', 'work_id');
        // });

        // 然後移除 collectable_type 欄位
        // Schema::table('collects', function (Blueprint $table) {
        //     $table->dropColumn('collectable_type');
        // });

        // 最後添加外鍵約束和唯一索引
        // Schema::table('collects', function (Blueprint $table) {
        //     $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
        //     $table->unique(['user_id', 'work_id']);
        // });
    }

    /**
     * 獲取表的所有外鍵名稱
     */
    private function getForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();
        $foreignKeys = [];

        // 取得表相關的所有外鍵
        foreach ($conn->listTableForeignKeys($table) as $foreignKey) {
            $foreignKeys[] = $foreignKey->getName();
        }

        return $foreignKeys;
    }

    /**
     * 檢查是否存在特定的外鍵
     */
    private function hasForeignKey($table, $column)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        foreach ($conn->listTableForeignKeys($table) as $foreignKey) {
            if (in_array($column, $foreignKey->getLocalColumns())) {
                return true;
            }
        }

        return false;
    }
};
