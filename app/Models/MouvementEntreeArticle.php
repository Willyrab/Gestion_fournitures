<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  


class MouvementEntreeArticle extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'mouvement_entree_article';

    // Clé primaire de la table
    protected $primaryKey = 'id_mouvement';

    // Désactiver les timestamps automatiques (created_at, updated_at)
    public $timestamps = false;

    // Les champs que l'on peut remplir
    protected $fillable = [
        'id_article',
        'nom_article',
        'description_article',
        'unite',
        'reference',
        'quantite',
        'prix',
        'id_gc',
        'id_fournisseur',
        'date_entree',
    ];

   

    // Relation avec l'article
    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    // Relation avec le gestionnaire du centre
    public function gestionnaireCentre()
    {
        return $this->belongsTo(GestionnaireCentre::class, 'id_gc', 'id_gc');
    }

    // Relation avec le fournisseur
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseurs::class, 'id_fournisseur', 'id_fournisseur');
    }
}