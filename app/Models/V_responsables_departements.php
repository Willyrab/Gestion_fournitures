<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_responsables_departements extends Model
{
    use HasFactory;
    
    protected $table = 'v_responsables_departements';
    public $incrementing = false; // Si ta vue n'a pas de clé primaire auto-incrémentée
    public $timestamps = false;

    protected $fillable = [
        'id_responsable_departement',
        'nom_responsable',
        'email',
        'id_departement',
        'nom_departement',
        'id_poste',
        'nom_poste',
    
    ];

    public static function getDepartementsByPoste($id_poste)
    {
        // Récupère les départements où le poste du responsable correspond au poste du gestionnaire
        return self::where('id_poste', $id_poste)->get();
    }
}
