<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'departements';
    protected $primaryKey = 'id_departement';
    protected $fillable = [
        'nom_departement',
    ];
}
