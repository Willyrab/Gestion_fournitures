<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BesoinsDepartement extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'besoins_departement';
    protected $primaryKey = 'id_besoin';

    protected $fillable = [
        'id_article',
        'quantite',
        'id_status',
        'id_responsable_departement',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    public function responsable()
    {
        return $this->belongsTo(ResponsableDepartement::class, 'id_responsable_departement');
    }
}
