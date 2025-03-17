<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlanGrade extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_plan_id', 'grade_level'];

    public function lessonPlan()
    {
        return $this->belongsTo(LessonPlans::class);
    }

    public function getGradeLevelAttribute($value)
    {
        $gradeLevels = [
            'elementary' => '國小',
            'junior_high' => '國中',
            'senior_high' => '高中'
        ];

        return $gradeLevels[$value] ?? $value;
    }
}
