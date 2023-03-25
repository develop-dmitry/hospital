<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorsSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'user_id');
    }
}
