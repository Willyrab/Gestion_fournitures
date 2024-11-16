<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseurs extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'fournisseurs';
    protected $primaryKey = 'id_fournisseur';
    protected $fillable = [
        'nom_fournisseur',
        'lieu_fournisseur',
        'email',
        'contact',
        'id_gc',
    ];

    public function poste()
    {
        return $this->belongsTo(Gestionnairecentre::class, 'id_gc', 'id_gc');
    }


}
