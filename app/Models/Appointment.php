<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'user_id',
        'visit_date',
        'visitor_name',
        'visitor_phone',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
