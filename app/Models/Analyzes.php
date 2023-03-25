<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Analyzes extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'link',
        'uploaded_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function uploadedByUser()
    {
        return $this->belongsTo(User::class, 'uploaded_user');
    }
}
