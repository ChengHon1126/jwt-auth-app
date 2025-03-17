<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_id',
        'image_path',
        'like',
        'progress',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'work_id', 'id');
    }

    // 修改为多态关联
    public function collects()
    {
        // 使用基本的 morphMany 方法，但不要添加 where 條件
        return $this->morphMany(Collects::class, 'collectable');
    }

    public function isCollectedBy($userId)
    {
        return $this->collects()->where('user_id', $userId)->exists();
    }


    // 統一回傳時間格式
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function ratings()
    {
        return $this->hasMany(WorkRating::class);
    }
    /**
     * 計算平均評分
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }

    /**
     * 獲取評分人數
     */
    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }
}
