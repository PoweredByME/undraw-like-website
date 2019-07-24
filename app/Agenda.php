<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use File;

class Agenda extends Model
{
    use Sluggable;

    protected $fillable = ['day_id', 'title', 'description', 'videoURL', 'video', 'videoBackgroundImage', 'videoBackgroundImageURL'];

    public function Day(){
        return $this->belongsTo(\App\Day::class);
    }

    public function deleteContent(){
        if($this->videoBackgroundImage != null
            && \App\Agenda::where('videoBackgroundImage', $this->videoBackgroundImage)->count() < 2
            && File::exists(public_path().$this->videoBackgroundImage))
        {
            File::delete(public_path().$this->videoBackgroundImage);
        }
        foreach($this->agendaKeyword as $i){$i->delete();}
    }

    public function sluggable(){
        return [
            'slug' => [
                'source' => ['title', 'day_id'],
                'seperator' => '_',
            ],
        ];
    }

    public function agendaKeyword(){
        return $this->hasMany(\App\agendaKeyword::class);
    }

}
