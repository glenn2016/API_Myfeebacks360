<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReponsesEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reponse',  
        'questions_evaluations_id',
        'niveau',
    ];
    public function questionsEvaluation()
    {
        return $this->belongsTo(QuestionsEvaluation::class, 'questions_evaluations_id');
    }
    public function question()
    {
        return $this->belongsTo(QuestionsEvaluation::class, 'questions_evaluations_id');
    }
    public function evaluation()
    {
        return $this->hasMany(EvaluationQuestionReponseEvaluation::class,'reponse_id');
    }

    public function evaluationQuestionReponseEvaluations()
    {
        return $this->hasMany(EvaluationQuestionReponseEvaluation::class, 'reponse_id');
    }

}