<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactAbonement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'numeroTelephone',
        'entreprise',
        'poste',
        'message',
        'telephoneFixe',
        'adressEntreprise',
        'ville',
        'pays',
    ];
}