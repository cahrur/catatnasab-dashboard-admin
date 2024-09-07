<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'provider',
        'payment_method',
        'api_key',
        'secret_key',
        'callback',
        'status'
    ];
}
