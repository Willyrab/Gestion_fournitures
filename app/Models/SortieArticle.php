<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SortieArticle extends Model
{
    use HasFactory;

    protected $table = 'sortie_articles';
    protected $primaryKey = 'id_sortie';

    protected $fillable = [
        'id_article',
        'quantite',
        'id_gc',
        'id_responsable_departement',
        'date_sortie',
    ];

    public $timestamps = false; 

        // Définir la relation avec le modèle Article
        public function article()
        {
            return $this->belongsTo(Article::class, 'id_article', 'id_article'); // Clé étrangère dans sortie_articles, clé primaire dans articles
        }
        
}
