<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class agendaKeyword extends Model
{
    protected $fillable = [
        'agenda_id', 'name'
    ];

    public function Agenda(){
        return $this->belongsTo(\App\Agenda::class);
    }
}
