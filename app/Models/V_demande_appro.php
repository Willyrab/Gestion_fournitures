<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_demande_appro extends Model
{
    use HasFactory;
   

    protected $table = 'v_demande_approvisionnement';
    public $incrementing = false; // Si ta vue n'a pas de clé primaire auto-incrémentée
    public $timestamps = false;
}
