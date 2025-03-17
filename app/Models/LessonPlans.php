<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlans extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'file_path',
        'grade_level',
        'teaching_goals',
        'activities',
        'is_approved'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grades()
    {
        return $this->hasMany(LessonPlanGrade::class, 'lesson_plan_id', 'id');
    }

    // 在LessonPlan模型中
    public function collects()
    {
        // 使用基本的 morphMany 方法，但不要添加 where 條件
        return $this->morphMany(Collects::class, 'collectable');
    }

    // 检查当前用户是否收藏了此教案
    public function isCollectedBy($userId)
    {
        return $this->collects()->where('user_id', $userId)->exists();
    }
}
