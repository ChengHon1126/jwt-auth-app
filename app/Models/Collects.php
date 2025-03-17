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
        'collectable_id',
        'collectable_type'
    ];
    public function collectable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
