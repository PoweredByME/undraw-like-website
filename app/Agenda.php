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
        if($this->videoBackgroundImage == null){return;}
        if(File::exists(public_path().$this->videoBackgroundImage)){
            File::delete(public_path().$this->videoBackgroundImage);
        }
    }

    public function sluggable(){
        return [
            'slug' => [
                'source' => ['title', 'day_id'],
                'seperator' => '_',
            ],
        ];
    }

}
