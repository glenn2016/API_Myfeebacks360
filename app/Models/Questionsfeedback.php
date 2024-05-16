<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionsfeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'evenement_id',
        'usercreate',
    ];

    public function reponsefeedback()
    {
        return $this->hasMany(Reponsefeedback::class);
    }

    public function feedback()
    {
        return $this->belongsTo(Feddback::class, 'feddback_id');
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    public function reponsefeedbacks()
    {
        return $this->hasMany(Reponsefeedback::class, 'questionsfeedbacks_id', 'id');
    }
  
}