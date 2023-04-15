<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'client_id',
        'doctor_id',
        'visit_date',
        'visit_time',
        'visitor_name',
        'visitor_phone',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'visit_time' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
