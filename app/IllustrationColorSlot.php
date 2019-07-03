<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IllustrationColorSlot extends Model
{
    public $fillable = [
        'color_id',
        'illustration_id',
    ];

    public function Illustration(){
        $this->belongsTo(\App\Illustration::class);
    }
}
