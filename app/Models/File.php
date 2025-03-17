<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $table = 'files';
    protected $fillable = [
        'work_id',
        'file_path',
        'status',
        'original_name', // 新增原始文件名
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }
}
