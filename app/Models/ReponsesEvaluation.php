<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReponsesEvaluation extends Model
{
    use HasFactory;


    public function questionsEvaluation()
    {
        return $this->belongsTo(QuestionsEvaluation::class, 'questions_evaluations_id');
    }

    public function evaluateur()
    {
        return $this->belongsTo(User::class,'evaluatuer_id');
    }

    public function evaluer()
    {
        return $this->belongsTo(User::class,'evaluer_id');
    }
}