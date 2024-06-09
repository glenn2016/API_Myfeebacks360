<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionsEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'evaluation_id',
        'categorie_id'
    ];


    public function reponsesEvaluation()
    {
        return $this->hasMany(ReponsesEvaluation::class,'questions_evaluations_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function categorie()
    {
        return $this->belongsToMany(Categorie::class, 'questions_evaluations_categorie', 'questions_evaluations_id', 'categorie_id');
    }

    public function reponses()
    {
        return $this->hasMany(ReponsesEvaluation::class, 'questions_evaluations_id');
    }


}
