<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeDevis extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'demande_devis';

    protected $primaryKey = 'id_demande';
    protected $fillable = [
        'nom_article',
        'description',
        'quantite',
        'unite',
        'date_demande',
        'date_limite',
        'nom_fournisseur',
        'lieu_fournisseur',
        'email_fournisseur',
        'contact',
        'nom_acheteur',
        'email_acheteur',
        'contact_acheteur',
        'id_poste',
        'id_status',
        'reference'
    ];
}
