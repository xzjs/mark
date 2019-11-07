<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MathMark extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }
}
