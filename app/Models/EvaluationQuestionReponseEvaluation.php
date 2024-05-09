<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationQuestionReponseEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reponse_id',
        'evaluatuer_id',
        'evaluer_id',
    ];

    public function evaluateur()
    {
        return $this->belongsTo(User::class,'evaluatuer_id');
    }
    public function evaluer()
    {
        return $this->belongsTo(User::class,'evaluer_id');
    }


}
