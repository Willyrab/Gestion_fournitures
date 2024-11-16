<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class GestionnaireCentre extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $table = 'gestionnaire_centre';
    protected $primaryKey = 'id_gc';
    protected $fillable = [
         'nom_gestionnaire','email','password','id_poste'
    ];
    

    public function poste()
    {
        return $this->belongsTo(Poste::class, 'id_poste', 'id_poste');
    }
}
