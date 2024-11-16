<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use Notifiable, HasFactory;

    public $timestamps = false;
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nom',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
