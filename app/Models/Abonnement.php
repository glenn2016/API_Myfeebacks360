<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'formule',
        'temps',
        'prix',
        'Abonnement_id',
    ];

    public function contactAbonement()
    {
        return $this->hasMany(ContactAbonement::class);
    }

    public function AbonnementUtlisateurs()
    {
        return $this->hasMany(AbonnementUtlisateurs::class);
    }

}
