<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    function makeControlOptions($user){
        return collect([
            ['title' => 'Edit Calendar', 'href' => '/admin/calendar/'.$user->id],
            ['title' => 'Edit Illustrations', 'href' => '/admin/illustrations/'.$user->id],
        ]);
    }

    function verifyUser($user_id){
        $loggedInUser = Auth::guard('admin')->user();
        $requestedUser = \App\Admin::find($user_id);

        abort_unless($requestedUser, 403);
        abort_unless($loggedInUser->id == $requestedUser->id, 403);

        return $loggedInUser;
    }

    public function show($user_id){

        $loggedInUser = $this->verifyUser($user_id);


        //dd($users);
        return view('admin.home', [
            'loggedInUser' => $loggedInUser,
            'requestedUser' => $loggedInUser,
            'options' => $this->makeControlOptions($loggedInUser),
        ]);
    }

    public function calendar($user_id){
        $loggedInUser = $this->verifyUser($user_id);
        return view('admin.calendar', [
            'loggedInUser' => $loggedInUser,
            'requestedUser' => $loggedInUser,
            'options' => $this->makeControlOptions($loggedInUser),
        ]);
    }


    public function createAgenda($user_id){
        $loggedInUser = $this->verifyUser($user_id);
        $data = request()->validate(
            [
                'date' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]
        );

        if(!\App\Day::where('date', $data['date'])->exists())
        {
            $day = \App\Day::create([
                'date' => $data['date'],
            ]);
        }else{
            $day = \App\Day::where('date', $data['date'])->first();
        }

        $agenda = \App\Agenda::create([
            'day_id' => $day->id,
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        if(request()->has('videoURL') && request()->has('videoBackgroundImage')){
            $agenda->videoURL = request()->videoURL;
            $image = request()->videoBackgroundImage;
            $publicPath = "agendaImages";
            $filename = $image->hashName();
            $image->move($publicPath, $filename);
            $image_path = "/". $publicPath."/". $filename;
            $agenda->videoBackgroundImage = $image_path;
            $agenda->video = true;
            $agenda->videoBackgroundImageURL = request()->videoBackgroundImageURL;
            $agenda->save();
        }

        request()->session()->flash('success', 'New agenda has been created ('.$data["date"].')');
        return back();

    }

    public function deleteAgenda($agenda_id, $user_id){
        $loggedInUser = $this->verifyUser($user_id);
        $agenda = \App\Agenda::find($agenda_id);
        abort_unless($agenda != null, 403);
        $day = $agenda->Day;
        $agenda->deleteContent();
        $agenda->delete();

        if($day->Agenda->count() == 0){
            $day->delete();
        }

        return back();
    }

    public function illustrations($user_id){
        $loggedInUser = $this->verifyUser($user_id);
        return view('admin.illustration', [
            'loggedInUser' => $loggedInUser,
            'requestedUser' => $loggedInUser,
            'options' => $this->makeControlOptions($loggedInUser),
        ]);
    }

    public function addIllustrationCatagory($user_id){
        $loggedInUser = $this->verifyUser($user_id);
        $data = request()->validate([
            'name' => 'required'
        ]);
        \App\IllustrationCatagory::create($data);

        request()->session()->flash('success', 'A new catagory has been added successfully');
        return back();
    }

    public function deleteIllustrationCatagory($catagory_id, $user_id){
        $loggedInUser = $this->verifyUser($user_id);
        $cat = \App\IllustrationCatagory::find($catagory_id);
        abort_unless($cat != null, 403);
        $cat->deleteContent();
        $cat->delete();
        request()->session()->flash('success', 'Catagory has been deleted successfully');
        return back();
    }

    public function createIllustration($user_id){
        $loggedInUser = $this->verifyUser($user_id);
        $data = request()->validate([
            'title' => 'required',
            'svg' => 'required',
            'illustration_catagory_id' => 'required',
        ]);

        $image = $data['svg'];
        $publicPath = "illustrations";
        $filename = $image->hashName();
        $filename = explode('.',$filename)[0].'.svg';

        $image->move($publicPath, $filename);
        $image_path = "/". $publicPath."/". $filename;

        $data['svg'] = $image_path;

        $ill = \App\Illustration::create($data);

        for($i=0;$i<6;$i++){
            if(request()->get('color_'.$i) != null){
                \App\IllustrationColorSlot::create([
                    'illustration_id' => $ill->id,
                    'color_id' => request()->get('color_'.$i),
                ]);
            }
        }

        for($i=0;$i<5;$i++){
            if(request()->get('keyword_'.$i) != null){
                \App\IllustrationKeyword::create([
                    'illustration_id' => $ill->id,
                    'name' => request()->get('keyword_'.$i),
                ]);
            }
        }

        request()->session()->flash('success', 'Illustration has been created successfully');
        return back();
    }

    public function deleteIllustration($illustration_id, $user_id){
        $loggedInUser = $this->verifyUser($user_id);
        $ill = \App\Illustration::find($illustration_id);
        \abort_unless($ill != null, 403);
        $ill->deleteContent();
        $ill->delete();
        request()->session()->flash('success', 'Illustration has been deleted successfully');
        return back();
    }

}
