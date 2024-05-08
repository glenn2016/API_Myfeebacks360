<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function questionsEvaluations()
    {
        return $this->hasMany(QuestionsEvaluation::class, 'categorie_id');
    }

    public function relatedQuestionsEvaluations() // Renommez cette méthode
    {
        return $this->belongsTo(QuestionsEvaluation::class, 'questions_evaluations_id');
    
    }
    public function questions()
    {
        return $this->hasMany(QuestionsEvaluation::class, 'categorie_id');
    }
}