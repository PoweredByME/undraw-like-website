<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IllustrationSubscription extends Model
{
    protected $fillable = ['illustration_id', 'type', 'name'];

    public function Illustration(){
        return $this->belongsTo(\App\Illustration::class);
    }
}
