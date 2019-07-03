<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IllustrationController extends Controller
{

    function _makeIllustration($ill){
        $_illustration = [
            'id' => $ill->id,
            'svg' => file_get_contents(public_path().$ill->svg),
            'title' => $ill->title,
            'catagory' => $ill->IllustrationCatagory->name,
            'color_slots' => [],
            'keywords' => [],
            'redirectTo' => url('/illustration/'.$ill->slug),
        ];
        foreach($ill->IllustrationColorSlot as $item){
            $_illustration['color_slots'][] = [
                'color_id' => $item->color_id,
            ];
        }
        foreach ($ill->IllustrationKeyword as $item) {
            $_illustration['keywords'][] = [
                'name' => $item->name,
            ];
        }
        return $_illustration;
    }

    public function getIllustrations($number_of_items){
        $illustrations = [];
        $illustrationDB = \App\Illustration::paginate($number_of_items);
        foreach($illustrationDB as $ill){

            $illustrations[] = $this->_makeIllustration($ill);
        }
        return [
            'illustrations' => $illustrations,
            'next_page_url' => $illustrationDB->nextPageUrl(),
        ];
    }

    public function showIllustration($slug){
        $ill = \App\Illustration::where('slug', $slug)->get()[0];
        abort_unless($ill != null, 404);

        $_illustration = $this->_makeIllustration($ill);
        return view('Illustration',['item' => $_illustration]);
    }

    public function fetchIllustration($slug){
        $ill = \App\Illustration::where('slug', $slug)->get()[0];
        abort_unless($ill != null, 404);
        return $this->_makeIllustration($ill);
    }
}
