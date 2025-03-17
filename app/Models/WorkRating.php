<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_id',
        'rating',
        'comment'
    ];

    /**
     * 獲取關聯的用戶
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 獲取關聯的作品
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
