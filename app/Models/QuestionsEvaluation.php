<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionsEvaluation extends Model
{
    use HasFactory;

    public function reponsesEvaluation()
    {
        return $this->hasMany(ReponsesEvaluation::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }


}
