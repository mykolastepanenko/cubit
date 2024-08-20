<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'confirmed', 'confirmed_by'];
}
