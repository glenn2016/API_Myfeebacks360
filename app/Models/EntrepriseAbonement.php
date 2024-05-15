<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseAbonement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom',
        'email',
        'numeroTelDeux',
        'numeroTelUn',
        'pays',
        'ville',
        'adresse',
        'usercreate',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
