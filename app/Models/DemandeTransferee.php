<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeTransferee extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'demande_transferer';
    protected $primaryKey = 'id_transfere';

    protected $fillable = [
        'id_nouveaubesoins',
        'date_envoi',
    
    ];
    public function nouveaubesoins()
    {
        return $this->belongsTo(Nouveaubesoins_departement::class, 'id_nouveaubesoins', 'id_nouveaubesoins');
    }
  
}
