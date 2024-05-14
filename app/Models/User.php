<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'categorie_id',
        'entreprise_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function hasRole(string $role): bool
    {
        return $this->roles()->where('nom', $role)->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function questionsfeedback()
    {
        return $this->hasMany(Questionsfeedback::class);
    }

    public function fedddbacks()
    {
        return $this->hasMany(Feddback::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function reponsefeedback()
    {
        return $this->hasMany(Reponsefeedback::class);
    }
    public function reponsesEvaluation()
    {
        return $this->hasMany(ReponsesEvaluation::class);
    }
    public function evaluateur()
    {
        return $this->hasMany(EvaluationQuestionReponseEvaluation::class);
    }
    public function evaluer()
    {
        return $this->hasMany(EvaluationQuestionReponseEvaluation::class);
    }



}
