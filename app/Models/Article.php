<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'articles';
    protected $primaryKey = 'id_article';

    protected $fillable = [
        'nom_article',
        'reference',
        'description',
        'unite',
        'id_gc',
        'id_categorie'
    ];

    public function getAllArticles()
    {
        return self::all(); // Utilise la méthode Eloquent "all" pour récupérer tous les articles
    }
    public function gestionnaire()
    {
        return $this->belongsTo(GestionnaireCentre::class, 'id_gc'); // Supposons que 'id_gc' soit la clé étrangère dans la table articles
    }
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie'); // Supposons que 'id_gc' soit la clé étrangère dans la table articles
    }
}
