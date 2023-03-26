<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Analysis extends Model
{
    use HasFactory;

    protected $table = 'analyzes';

    protected $fillable = [
        'user_id',
        'name',
        'link',
        'uploaded_doctor',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function uploadedByDoctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
