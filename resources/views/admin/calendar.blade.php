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
                    <p class="m-0 mt-2">Title</p>
                    <input type="text" name="title" required placeholder="Enter the title of the agenda" class="form-control" />
                    <p class="m-0 mt-2">Description</p>
                    <input type="text" name="description" required placeholder="Enter the description of the agenda" class="form-control" />
                    <p class="m-0 mt-2">Video URL</p>
                    <input type="text" name="videoURL"  placeholder="Enter the description of the agenda" class="form-control" />
                    <p class="m-0 mt-2">Images video background URL</p>
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
                    if(form.date.value == '' || form.title.value == '' || form.description.value == ''){
                        alert('Please fill the required field. (Date, Title and Description)');
                    }else if((form.videoURL.value == '' && form.videoBackgroundImage.value != '') || (form.videoURL.value != '' && form.videoBackgroundImage.value == '')){
                        alert('The fields video url and video background should, both should either be filled or both should be left unfilled');
                    }else{
                        form.submit();
                    }
                ">Submit</button>
            </template>
        </v-modal>

        <div class="mt-5">
            @foreach(\App\Day::all() as $day)
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
                                <button onclick="confirm('Are you sure you want to delete it?')? document.getElementById('delete-agenda-{{ $day->id }}-{{ $agenda->id }}').submit() : -1" class="btn btn-danger"><i class="fa fa-trash mr-2"></i>Delete</button>
                            </template>
                        </va-card>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection()

