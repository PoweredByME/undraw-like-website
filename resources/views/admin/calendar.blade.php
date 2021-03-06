@extends('admin.layout.adminHome')

@section('title', 'Manage Calendar')

@section('content')
    <div class='p-5' id="app">
        @component('Master.onSuccessFlashMessage')
        @endcomponent()
        <v-modal name="New Agenda" title="Add a New Agenda" button_title="Add New Agenda">
            <div style="height:500px;overflow-y:auto">
                <form method="post" enctype="multipart/form-data" id="add-new-agenda" class="mt-1" action="/admin/calendar/agenda/{{ $loggedInUser->id }}">
                    @csrf
                    <p class="m-0">Pick a date</p>
                    <input type="date" name="date" class="form-control" required>
                    <p class="mb-1 mt-1">
                            <input type="checkbox" name="repeat" value="1">
                            Repeat this event for 10 years
                    </p>
                    <p class="m-0 mt-2">Title</p>
                    <input type="text" name="title" required placeholder="Enter the title of the agenda" class="form-control" />
                    <p class="m-0 mt-2">Description</p>
                    <input type="text" name="description" required placeholder="Enter the description of the agenda" class="form-control" />
                    <p class="m-0 mt-2">Video URL</p>
                    <input type="text" name="videoURL"  placeholder="Enter the description of the agenda" class="form-control" />
                    <p class="m-0 mt-2">Image for video preview</p>
                    <input type="text" name="videoBackgroundImageURL"  placeholder="Enter the video Background Image URL" class="form-control" />
                    <p class="mb-1 mt-1">Images video background</p>
                    <input type="file" name="videoBackgroundImage"  accept="image/*" />
                    <h4 class="mt-3">Event Keywords</h4>
                    <p class="mb-1">Please enter 5 keywords for the Illustration</p>
                    @for($i=0;$i<5;$i++)
                        <input type="text" name="keyword_{{ $i }}" placeholder="Enter the keyword {{ $i }}" class="mb-2 form-control"/>
                    @endfor
                </form>
            </div>
            <template v-slot:footer>
                <button role="btn" class="btn btn-primary" onclick="
                    var form = document.getElementById('add-new-agenda');
                    form.submit();
                    /*if(form.date.value == '' || form.title.value == '' || form.description.value == ''){
                        alert('Please fill the required field. (Date, Title and Description)');
                    }else if((form.videoURL.value == '' && form.videoBackgroundImage.value != '') || (form.videoURL.value != '' && form.videoBackgroundImage.value == '')){
                        alert('The fields video url and video background should, both should either be filled or both should be left unfilled');
                    }else{

                    }*/
                ">Submit</button>
            </template>
        </v-modal>

        <br>
        <v-modal name="Import from CSV" title="Import from CSV" button_title="Import from CSV">
            <div style="height:100px;overflow-y:auto">
                <form method="post" enctype="multipart/form-data" id="import-from-csv" class="mt-1" action="/admin/calendar/csv/{{ $loggedInUser->id }}">
                    @csrf
                    <p class="m-0">Upload the csv file</p>
                    <input type="file" name="csv" accept=".csv" class="form-control" required>
                </form>
            </div>
            <template v-slot:footer>
                <button role="btn" class="btn btn-primary" onclick="
                    var form = document.getElementById('import-from-csv');
                    form.submit();
                ">Submit</button>
            </template>
        </v-modal>

        <p class="mt-2">
            The header of the the CSV should be of the following formate
            <br>
            date,title,description,video URL,image,image URL,keyword 1,keyword 2,keyword 3,keyword 4,keyword 5, repeat
        </p>

        <div class="mt-5">
            @foreach(\App\Day::all()->forPage($page, 10)->all() as $day)
                <div>
                    <div class="d-flex">
                        <h5 style="background: #323232; color:white;border-radius:5px" class="p-2">{{ $day->date }}</h5>
                    </div>
                    @foreach($day->Agenda as $agenda)
                        <va-card>
                            <div class="d-flex align-items-center">
                                <p class="m-0">{{ $loop->iteration }}.</p>
                                <div class="ml-3">
                                    <p class="m-0">Date : {{ $agenda->Day->date }}</p>
                                    <p class="m-0">Title : {{ $agenda->title }}</p>
                                    <p class="m-0">Description : {{ str_limit($agenda->description, 40, '...') }}</p>
                                    @if($agenda->videoURL != null)
                                        <p class="m-0">Video URL : {{ $agenda->videoURL }}</p>
                                        <a href="{{ $agenda->videoBackgroundImage }}" target="_blank">View video background image.</a>
                                    @endif
                                    <p class="m-0">Keywords :
                                            @foreach($agenda->agendaKeyword as $item)
                                                {{ $item->name }},&nbsp
                                            @endforeach
                                    </p>
                                </div>
                            </div>
                            <template v-slot:buttons>
                                <form action="/admin/calendar/agenda/{{ $agenda->id }}/{{ $loggedInUser->id }}" method="post" id="delete-agenda-{{ $day->id }}-{{ $agenda->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button onclick="confirm('Are you sure you want to delete it?')? document.getElementById('delete-agenda-{{ $day->id }}-{{ $agenda->id }}').submit() : -1" class="btn btn-danger mb-1"><i class="fa fa-trash mr-2"></i>Delete</button>

                                <v-modal name="edit-agenda-modal-{{ $agenda->id }}" title="Edit Agenda" button_title="Edit Agenda">
                                    <div style="height:500px;overflow-y:auto">
                                        <form method="post" enctype="multipart/form-data" id="edit-agenda-{{ $agenda->id }}" class="mt-1" action="/admin/calendar/agenda/edit/{{ $agenda->id }}/{{ $loggedInUser->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <p class="m-0">Pick a date</p>
                                            <input type="date" name="date" class="form-control" value="{{ $agenda->Day->date }}">
                                            <p class="m-0 mt-2">Title</p>
                                            <input type="text" name="title" value="{{ $agenda->title }}" placeholder="Enter the title of the agenda" class="form-control" />
                                            <p class="m-0 mt-2">Description</p>
                                            <input type="text" name="description" value="{{ $agenda->description }}" placeholder="Enter the description of the agenda" class="form-control" />
                                            <p class="m-0 mt-2">Video URL</p>
                                            <input type="text" name="videoURL" value="{{ $agenda->videoURL }}" placeholder="Enter the description of the agenda" class="form-control" />
                                            <p class="m-0 mt-2">Image for video preview SOURCE</p>
                                            <input type="text" name="videoBackgroundImageURL" value="{{ $agenda->videoBackgroundImageURL }}"  placeholder="Enter the video Background Image URL" class="form-control" />
                                            <p class="mb-1 mt-1">Image for video preview </p>
                                            <input type="file" name="videoBackgroundImage"  accept="image/*" />
                                            <h4 class="mt-3">Event Keywords</h4>
                                            <p class="mb-1">Please enter 5 keywords for the Illustration</p>
                                            @for($i=0;$i<5;$i++)
                                                <input type="text" name="keyword_{{ $i }}" placeholder="Enter the keyword {{ $i }}" class="mb-2 form-control"
                                                    value="{{ count($agenda->agendaKeyword) > $i ? $agenda->agendaKeyword[$i]->name:''}}"
                                                />
                                            @endfor
                                        </form>
                                    </div>
                                    <template v-slot:footer>
                                        <button role="btn" class="btn btn-primary" onclick="
                                            var form = document.getElementById('edit-agenda-{{ $agenda->id }}');
                                            form.submit();
                                        ">Submit</button>
                                    </template>
                                </v-modal>

                            </template>
                        </va-card>
                    @endforeach
                </div>
            @endforeach
        </div>


        <h4>Pages</h4>
        <div class="d-flex align-items-center justify-content-center flex-wrap p-3">
                @for($_i = 1; $_i <= ceil(\App\Day::all()->count() / 10); $_i++)
                    <a href="/admin/calendar/{{ $loggedInUser->id }}/{{ $_i }}" class="m-1" style="margin:0;padding:0;padding:8px;border-radius:100px;background-color:{{ $page == $_i ? '#212121' : 'orange' }};color:white">{{ $_i }}</a>
                @endfor
            </div>

    </div>
@endsection()

