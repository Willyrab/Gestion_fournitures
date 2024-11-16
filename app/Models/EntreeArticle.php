<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;           
use App\Models\GestionnaireCentre; 
use App\Models\Fournisseurs;    

class EntreeArticle extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'entree_articles';

    public $timestamps = false;

    
    protected $primaryKey = 'id_entree';

    // Déclaration des colonnes accessibles en masse (mass assignable)
    protected $fillable = [
        'id_article',
        'quantite',
        'id_gc',
        'prix',
        'id_fournisseur',
        'date_entree',
    ];

    // Les relations

    // Relation avec la table articles (1:N)
    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article');
    }

    // Relation avec la table gestionnaire_centre (1:N)
    public function gestionnaire()
    {
        return $this->belongsTo(GestionnaireCentre::class, 'id_gc');
    }

    // Relation avec la table fournisseurs (1:N)
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseurs::class, 'id_fournisseur');
    }

    // Optionnel : vous pouvez également définir un format de date pour la colonne `date_entree` si nécessaire
    protected $casts = [
        'date_entree' => 'date',
    ];
}
