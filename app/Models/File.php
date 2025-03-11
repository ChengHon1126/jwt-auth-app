<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'file_path',
        'status',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
