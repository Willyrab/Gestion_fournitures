<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchatFournisseur extends Model
{
    use HasFactory;

    protected $table = 'achat_fournisseur';
    public $timestamps = false;
    protected $primaryKey = 'id_achat';
    protected $fillable = [
        'id_article',
        'quantite',
        'prix',
        'nom_acheteur',
        'email_acheteur',
        'contact_acheteur',
        'date_achat',
        'id_fournisseur',
        'id_status',
        'id_poste',
        'reference',
        'lieu_livraison',
        'condition_paiement',
        'id_departement',
        'date_livraison',
    ];
}
