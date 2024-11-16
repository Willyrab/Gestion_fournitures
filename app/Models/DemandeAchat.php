<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeAchat extends Model
{
    use HasFactory;
    protected $table = 'demande_achat';
    public $timestamps = false;
    protected $primaryKey = 'id_demande';

    // Définir les colonnes qu'on peut remplir via insertion de masse
    protected $fillable = [
        'id_nouveaubesoins',
        'date_demande',
        'id_status',
        'id_gc',
        'quantite'
    ];
}
