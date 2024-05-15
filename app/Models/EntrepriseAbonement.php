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
        'numeorTeelUn',
        'numeorTeelDeux',
        'pays',
        'ville',
        'adresse',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
