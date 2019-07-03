<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IllustrationKeyword extends Model
{
    public $fillable = [
        'name',
        'illustration_id',
    ];

    public function Illustration(){
        return $this->belongsTo(\App\Illustration::class);
    }
}
