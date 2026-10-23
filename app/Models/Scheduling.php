<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheduling extends Model
{
    protected $table = 'scheduling';

    protected $fillable = [
        'responsible_name',
        'responsible_email',
        'phone_number',
        'motivation',
        'people_quantity',
        'scheduled_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'people_quantity' => 'integer',
            'scheduled_at' => 'datetime',
        ];
    }
}
