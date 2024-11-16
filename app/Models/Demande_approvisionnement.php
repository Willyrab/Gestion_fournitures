<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande_approvisionnement extends Model
{
    use HasFactory;
    protected $table = 'demande_approvisionnement';
    protected $primaryKey = 'id_demandeapp';
    protected $fillable = [
        'id_article', 'id_gc', 'date_demande', 'id_status'
    ];

    public $timestamps = false; // Si tu n'as pas de colonnes created_at/updated_at

    public function poste()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }
}
