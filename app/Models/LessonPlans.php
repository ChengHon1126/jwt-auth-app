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
}
