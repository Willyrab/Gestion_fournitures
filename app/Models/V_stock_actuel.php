<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_stock_actuel extends Model
{
    use HasFactory;
    protected $table = 'v_stock_actuel';
    public $incrementing = false; // Si la vue n'a pas de clé primaire auto-incrémentée
    public $timestamps = false;

    protected $fillable = [
        'id_article', 
        'reference_article', 
        'nom_article', 
        'unite', 
        'nom_categorie', 
        'total_entrees', 
        'gestionnaire_entree',
        'total_sorties', 
        'stock_actuel'
    ];

    public static function articlesEnApprovisionnement($id_gc)
    {
        return self::where('stock_actuel', '<=', 5)
                    ->where('gestionnaire_entree', $id_gc) 
                    ->get();
    }
}
