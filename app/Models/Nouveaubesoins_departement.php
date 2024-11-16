<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nouveaubesoins_departement extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'nouveaubesoins_departement';
    protected $primaryKey = 'id_nouveaubesoins';

    protected $fillable = [
        'id_nouveaubesoins',
        'id_responsable_departement',
        'nom_article',
        'description',
        'quantite',
        'unite',
        'date_demande',
        'id_status',
        'motif_demande',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status'); // Ajustez si nécessaire
    }
    public function demandeTransferee()
    {
        return $this->hasOne(DemandeTransferee::class, 'id_nouveaubesoins', 'id_nouveaubesoins');
    }

    public function responsableDepartement()
{
    return $this->belongsTo(ResponsableDepartement::class, 'id_responsable_departement');
}

}
