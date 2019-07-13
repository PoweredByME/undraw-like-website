<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;
use Auth;
use Storage;
use Image;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\Stub\Exception;

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

    public function calendarImportFromCSV($user_id){
        $this->verifyUser($user_id);
        $reader = Reader::createFromFileObject(request()->file('csv')->openFile());
        $firstRow = true;
        $i = [
            "date" => 0,
            "title" => 1,
            "description" => 2,
            "videoURL" => 3,
            "image" => 4,
            "imageURL" => 5,
            "keyword0" => 6,
            "keyword1" => 7,
            "keyword2" => 8,
            "keyword3" => 9,
            "keyword4" => 10,
        ];
        foreach ($reader->getRecords() as $index => $row) {
            if($firstRow){
                $firstRow = false;
                continue;
            }

            $carbon = new Carbon($row[$i['date']]);
            $_date = $carbon->year.'-'.$carbon->month.'-'.$carbon->day;
            if(!\App\Day::where('date', $_date)->exists())
            {
                $day = \App\Day::create([
                    'date' => $_date,
                ]);
            }else{
                $day = \App\Day::where('date', $_date)->first();
            }

            $agenda = \App\Agenda::create([
                'day_id' => $day->id,
                'title' => $row[$i['title']],
                'description' => $row[$i['description']],
            ]);

            try{
                if($row[$i['videoURL']] != '' && $row[$i['image']] != ''){
                    $agenda->videoURL = $row[$i['videoURL']];
                    $url = $row[$i['image']];
                    $image = Image::make($url);
                    $publicPath = "agendaImages";
                    $filename = microtime(true).basename($url);
                    $image_path = "/". $publicPath."/". $filename;
                    $image->save(public_path($image_path));
                    $agenda->videoBackgroundImage = $image_path;
                    $agenda->video = true;
                    $agenda->videoBackgroundImageURL = $row[$i["imageURL"]] ;
                    $agenda->save();
                }
            }catch(Exception $err){
                $agenda->deleteContent();
                $agenda->delete();
                request()->session()->flash('failure', 'There is a problem in uploading on of the images at row '. $index);
                return back();
            }

            for($_i=0;$_i<5;$_i++){
                if($row[$i['keyword'.$_i]] != ''){
                    \App\agendaKeyword::create([
                        'agenda_id' => $agenda->id,
                        'name' => $row[$i['keyword'.$_i]],
                    ]);
                }
            }

        }



        return back();
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

        for($i=0;$i<5;$i++){
            if(request()->get('keyword_'.$i) != null){
                \App\agendaKeyword::create([
                    'agenda_id' => $agenda->id,
                    'name' => request()->get('keyword_'.$i),
                ]);
            }
        }

        request()->session()->flash('success', 'New agenda has been created ('.$data["date"].')');
        return back();

    }

    public function editAgenda($agenda_id, $user_id){

        $loggedInUser = $this->verifyUser($user_id);
        $agenda = \App\Agenda::find($agenda_id);
        abort_unless($agenda != null, 403);
        if(request()->has('date')){
            $day = \App\Day::where('date',request()->date)->first();
            if(request()->date != $agenda->Day->date && $day == null){
                $day = \App\Day::create([
                    'date'=>request()->date,
                ]);
            }
        }else{
            $day = $agenda->Day;
        }


        $agenda->day_id = $day->id;
        $agenda->title = request()->has('title') ? request()->title : $agenda->title;
        $agenda->description = request()->has('description') ? request()->description : $agenda->description;
        $agenda->videoURL = request()->has('videoURL') ? request()->videoURL : $agenda->videoURL;

        $agenda->videoBackgroundImageURL = request()->has('videoBackgroundImageURL') ? request()->videoBackgroundImageURL : $agenda->videoBackgroundImageURL;


        if(request()->has('videoBackgroundImage')){
            $agenda->deleteContent();
            $image = request()->videoBackgroundImage;
            $publicPath = "agendaImages";
            $filename = $image->hashName();
            $image->move($publicPath, $filename);
            $image_path = "/". $publicPath."/". $filename;
            $agenda->videoBackgroundImage = $image_path;
        }else{
            foreach($agenda->agendaKeyword as $i){$i->delete();}
        }

        for($i=0;$i<5;$i++){
            if(request()->get('keyword_'.$i) != null){
                \App\agendaKeyword::create([
                    'agenda_id' => $agenda->id,
                    'name' => request()->get('keyword_'.$i),
                ]);
            }
        }

        $agenda->save();

        if (
                ($agenda->videoURL != null || $agenda->videoURL != "")
            &&  ($agenda->videoBackgroundImage != null || $agenda->videoBackgroundImage != "")
        ){
            $agenda->video = true;
        }else{
            $agenda->video = false;
        }

        $agenda->save();
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

    public function illustrationImportFromCSV($user_id){
        $this->verifyUser($user_id);
        $reader = Reader::createFromFileObject(request()->file('csv')->openFile());
        $firstRow = true;
        $i = [
            "title" => 0,
            "svg" => 1,
            "catagory" => 2,
            "keyword0" => 3,
            "keyword1" => 4,
            "keyword2" => 5,
            "keyword3" => 6,
            "keyword4" => 7,
            "color0" => 8,
            "color1" => 9,
            "color2" => 10,
            "color3" => 11,
            "color4" => 12,
            "color5" => 13,
        ];
        foreach ($reader->getRecords() as $index => $row) {
            if($firstRow){
                $firstRow = false;
                continue;
            }

            $cat = \App\IllustrationCatagory::where('name', $row[$i['catagory']])->first();
            if(!$cat){
                request()->session()->flash('failure', 'The catagory do not exist in the database. Please, make sure the catagory aleardy exists in the database. Error at row ' . $index);
                return back();
            }

            try{
                $svg = $row[$i['svg']];
                if($svg == "") {
                    request()->session()->flash('failure', 'Please put a valid svg content in the "svg"  column. Error row '. $index);
                    return back();
                }
                $publicPath = "illustrations";
                $filename = '/' . $publicPath .'/'.microtime(true).'.svg';
                file_put_contents(public_path($filename), $svg);

                $ill = \App\Illustration::create([
                    'title' => $row[$i['title']],
                    'svg' => $filename,
                    'illustration_catagory_id' => $cat->id,
                ]);

                for($_i=0;$_i<6;$_i++){
                    if($row[$i['color'.$_i]] != ''){
                        \App\IllustrationColorSlot::create([
                            'illustration_id' => $ill->id,
                            'color_id' => $row[$i['color'.$_i]],
                        ]);
                    }
                }

                for($_i=0;$_i<5;$_i++){
                    if($row[$i['keyword'.$_i]] != ''){
                        \App\IllustrationKeyword::create([
                            'illustration_id' => $ill->id,
                            'name' => $row[$i['keyword'.$_i]],
                        ]);
                    }
                }

            }catch(Exception $err){
                request()->session()->flash('failure', 'Some error occured. Error row '. $index);
                $ill->deleteContent();
                $ill->delete();
                return back();
            }

        }
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
        abort_unless($ill != null, 404);
        $ill->deleteContent();
        $ill->delete();
        request()->session()->flash('success', 'Illustration has been deleted successfully');
        return back();
    }

    public function editIllustration($illustration_id, $user_id){
        $loggedInUser = $this->verifyUser($user_id);
        $ill = \App\Illustration::find($illustration_id);
        abort_unless($ill != null, 404);
        $ill->title = request()->has('title') ? request()->title : $ill->title;
        $ill->illustration_catagory_id = request()->has('illustration_catagory_id') ? request()->illustration_catagory_id : $ill->illustration_catagory_id;
        if(request()->has('svg')){
            $ill->deleteContent();
            $image = request()->svg;
            $publicPath = "illustrations";
            $filename = $image->hashName();
            $filename = explode('.',$filename)[0].'.svg';

            $image->move($publicPath, $filename);
            $image_path = "/". $publicPath."/". $filename;
            $ill->svg = $image_path;
        }else{
            foreach($ill->IllustrationColorSlot as $i){$i->delete();}
            foreach($ill->IllustrationKeyword as $i){$i->delete();}
        }
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
        $ill->save();
        return back();
    }
}
