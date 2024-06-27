<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepondreQuestionsEvenebeemnt extends Model
{
    use HasFactory;

    protected $fillable = [
        'reponsefeedback_id',
        'email',
        'questionsfeedbacks_id',
        'repondre'
    ];

    public function reponsefeedback()
    {
        return $this->belongsTo(Reponsefeedback::class, 'reponsefeedback_id');
    }

    public function questionsfeedback()
    {
        return $this->belongsTo(Questionsfeedback::class,'questionsfeedbacks_id');
    }


}
