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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public static function getAppointmentsByDate($date, $doctorId)
    {
        return self::whereDate('visit_date', $date)
            ->where('doctor_id', $doctorId)
            ->get()
            ->toArray();
    }
}
