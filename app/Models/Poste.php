<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'poste';
    protected $primaryKey = 'id_poste';
    protected $fillable = [
         'nom_poste','lieu_poste'
    ];
}
