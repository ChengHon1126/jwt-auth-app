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
            // 檢查並添加 approved_user_id 欄位
            if (!Schema::hasColumn('lesson_plans', 'approved_user_id')) {
                $table->foreignId('approved_user_id')->nullable()->constrained('users')->onDelete('set null')->after('is_approved')->comment('審核人員');
            }

            // 檢查並添加 approved_at 欄位
            if (!Schema::hasColumn('lesson_plans', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_user_id')->comment('審核時間');
            }

            // 檢查並添加 is_delete 欄位
            if (!Schema::hasColumn('lesson_plans', 'is_delete')) {
                if (Schema::hasColumn('lesson_plans', 'approved_at')) {
                    $table->boolean('is_delete')->default(false)->after('approved_at')->comment('是否刪除');
                } else {
                    $table->boolean('is_delete')->default(false)->comment('是否刪除');
                }
            }

            // 檢查並添加 deleted_user_id 欄位
            if (!Schema::hasColumn('lesson_plans', 'deleted_user_id')) {
                if (Schema::hasColumn('lesson_plans', 'is_delete')) {
                    $table->foreignId('deleted_user_id')->nullable()->constrained('users')->onDelete('set null')->after('is_delete')->comment('刪除人員');
                } else {
                    $table->foreignId('deleted_user_id')->nullable()->constrained('users')->onDelete('set null')->comment('刪除人員');
                }
            }

            // 檢查並添加 deleted_at 欄位
            if (!Schema::hasColumn('lesson_plans', 'deleted_at')) {
                if (Schema::hasColumn('lesson_plans', 'deleted_user_id')) {
                    $table->timestamp('deleted_at')->nullable()->after('deleted_user_id')->comment('刪除時間');
                } else {
                    $table->timestamp('deleted_at')->nullable()->comment('刪除時間');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_plans', function (Blueprint $table) {
            // 檢查並刪除欄位
            if (Schema::hasColumn('lesson_plans', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }

            if (Schema::hasColumn('lesson_plans', 'deleted_user_id')) {
                // 先檢查外鍵是否存在
                $foreignKeys = $this->getForeignKeys('lesson_plans');
                $deletedUserForeignKey = null;
                foreach ($foreignKeys as $foreignKey) {
                    if (str_contains($foreignKey, 'deleted_user_id')) {
                        $deletedUserForeignKey = $foreignKey;
                        break;
                    }
                }

                if ($deletedUserForeignKey) {
                    $table->dropForeign($deletedUserForeignKey);
                }
                $table->dropColumn('deleted_user_id');
            }

            if (Schema::hasColumn('lesson_plans', 'is_delete')) {
                $table->dropColumn('is_delete');
            }

            if (Schema::hasColumn('lesson_plans', 'approved_at')) {
                $table->dropColumn('approved_at');
            }

            if (Schema::hasColumn('lesson_plans', 'approved_user_id')) {
                // 先檢查外鍵是否存在
                $foreignKeys = $this->getForeignKeys('lesson_plans');
                $approvedUserForeignKey = null;
                foreach ($foreignKeys as $foreignKey) {
                    if (str_contains($foreignKey, 'approved_user_id')) {
                        $approvedUserForeignKey = $foreignKey;
                        break;
                    }
                }

                if ($approvedUserForeignKey) {
                    $table->dropForeign($approvedUserForeignKey);
                }
                $table->dropColumn('approved_user_id');
            }
        });
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
};
