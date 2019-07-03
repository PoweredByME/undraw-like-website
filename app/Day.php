<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $fillable = ['date'];

    public function Agenda(){
        return $this->hasMany(\App\Agenda::class);
    }
}
