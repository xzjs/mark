<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function subjects(){
        return $this->belongsToMany('App\Subject');
    }
}
