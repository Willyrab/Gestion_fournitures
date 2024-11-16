<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_entree_article extends Model
{
    use HasFactory;
  
   
    protected $table = 'v_entree_article';
    public $incrementing = false; // Si ta vue n'a pas de clé primaire auto-incrémentée
    public $timestamps = false;

    protected $fillable = [
        'id_entree',
        'reference_article',
        'nom_article',
        'unite',
        'quantite',
        'prix',
        'fournisseur',  
        'date_entree',
        'id_gc',
        'gestionnaire_centre',
    ];
 
}
