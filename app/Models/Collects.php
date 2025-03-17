<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collects extends Model
{
    use HasFactory;
    protected $table = 'collects';
    protected $fillable = [
        'user_id',
        'work_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    /**
     * 為模型的數組或 JSON 序列化準備日期
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }
}
