<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use File;

class Illustration extends Model
{
    use Sluggable;

    public $fillable = [
        'svg',
        'title',
        'illustration_catagory_id',
    ];

    public function IllustrationCatagory(){
        return $this->belongsTo(\App\IllustrationCatagory::class);
    }

    public function IllustrationColorSlot(){
        return $this->hasMany(\App\IllustrationColorSlot::class);
    }

    public function IllustrationKeyword(){
        return $this->hasMany(\App\IllustrationKeyword::class);
    }

    public function deleteContent(){
        foreach($this->IllustrationColorSlot as $i){$i->delete();}
        foreach($this->IllustrationKeyword as $i){$i->delete();}
        if(File::exists(public_path().$this->svg)){
            File::delete(public_path().$this->svg);
        }
    }

    public function sluggable(){
        return [
            'slug' => [
                'source' => ['title', 'id'],
                'seperator' => '_',
            ],
        ];
    }

}
