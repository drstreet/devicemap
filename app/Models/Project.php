<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'device_id',
        'csv_file',
        'background_image',
        'user_id',
        'x1',
        'y1',
        'x2',
        'y2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
