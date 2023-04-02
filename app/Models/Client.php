<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'external_id',
        'telegram_login',
        'first_name',
        'last_name',
        'uuid'
    ];
}
