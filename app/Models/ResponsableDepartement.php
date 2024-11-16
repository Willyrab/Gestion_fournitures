<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ResponsableDepartement extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $table = 'responsable_departement';
    protected $primaryKey = 'id_responsable_departement';
    protected $fillable = [
         'nom_responsable','email','password','id_departement','id_poste'
    ];
    public function poste()
    {
        return $this->belongsTo(Poste::class, 'id_poste', 'id_poste');
    }
    public function postes()
    {
        return $this->belongsTo(Departement::class, 'id_departement', 'id_departement');
    }
}
