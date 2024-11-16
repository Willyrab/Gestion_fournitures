<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_nouveaubesoins_article extends Model
{
    use HasFactory;
    protected $table = 'v_nouveaubesoins_departement';
    public $incrementing = false; // Si ta vue n'a pas de clé primaire auto-incrémentée
    public $timestamps = false;
}
