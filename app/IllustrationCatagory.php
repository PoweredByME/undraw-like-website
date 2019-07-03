<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IllustrationCatagory extends Model
{
    public $fillable = ['name'];

    public function Illustration(){
        return $this->hasMany(\App\Illustration::class);
    }

    public function deleteContent(){
        // If you want to delete a catagory then this
        // function will remove the catagory form all
        // the Illustrations in belonging to it.
        foreach($this->Illustration as $i){
            $i->illustration_catagory_id = null;
        }
    }
}
