<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
     protected $table = 'categorie';

    // Clé primaire de la table
    protected $primaryKey = 'id_categorie';

    // Désactiver les timestamps automatiques (created_at, updated_at)
    public $timestamps = false;

     protected $fillable = [
        'nom_categorie',
        
    ];

}
