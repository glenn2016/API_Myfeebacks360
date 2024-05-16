<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'etat',
        'usercreate',
    ];

    public function questionsEvaluation()
    {
        return $this->hasMany(QuestionsEvaluation::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
