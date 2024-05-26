<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonnementUtlisateurs extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_fin_abonement',
        'date_debut_abonement',
        'utlisateur_id',
        'abonnement_id',
    ];

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class, 'abonnement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'utlisateur_id');
    }
}
