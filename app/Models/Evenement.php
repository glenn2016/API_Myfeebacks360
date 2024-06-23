<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'token',
    ];

    public function questionsfeedback()
    {
        return $this->hasMany(Questionsfeedback::class);
    }

    public function usercreate()
    {
        return $this->hasMany(User::class,'usercreate');
    }
}
