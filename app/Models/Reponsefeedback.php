<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponsefeedback extends Model
{
    protected $fillable = [
        'questionsfeedbacks_id',
        'nom',
    ];
    use HasFactory;
    public function questionsfeedback()
    {
        return $this->belongsTo(Questionsfeedback::class, 'questionsfeedbacks_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reponseSelections()
    {
        return $this->hasMany(RepondreQuestionsEvenebeemnt::class);
    }
}
