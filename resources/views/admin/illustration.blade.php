@extends('admin.layout.adminHome')

@section('title', 'Manage Illustrations')

@section('content')

    <div class="p-5" id="app">
        @component('Master.onSuccessFlashMessage')
        @endcomponent()
        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#add-catagory-modal">Add Illustration Catagory</button>

        <div role="dialog" tabindex="-1" class="modal fade" id="add-catagory-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add an Illustration Catagory</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            @foreach(\App\IllustrationCatagory::all() as $cat)
                                <div style="border-radius:5px" class="mb-2 p-2 calendar-bg-light-green d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="m-0">{{ $loop->iteration }}. {{ $cat->name }}</p>
                                    </div>
                                    <div>
                                        <form id="delete-cat-form-{{ $cat->id }}" method="POST" action="/admin/illustration/catagory/{{ $cat->id }}/{{ $loggedInUser->id }}">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        <button role='btn' class="btn btn-danger"
                                            onclick="confirm('Are you sure you want to delete this catagory?') ? document.getElementById('delete-cat-form-{{ $cat->id }}').submit() : -1;"
                                        ><i class="fa fa-trash mr-2"></i>Delete</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <form id="add-catagory-form" method="POST" action="/admin/illustration/catagory/{{ $loggedInUser->id }}">
                            @csrf
                            <p>Name of Catagory</p>
                            <input type="text" name="name" placeholder="Enter the name of catagory" class="form-control" required/>
                            @component('Master.formValidationError', ['param' => 'name'])
                            @endcomponent()
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" role="button" onclick="document.getElementById('add-catagory-form').submit()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <button class="ml-5 btn btn-warning" type="button" data-toggle="modal" data-target="#add-illustration-modal">Add A New Illustration</button>



        <div role="dialog" tabindex="-1" class="modal fade" id="add-illustration-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add an Illustration</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" id="add-illustration-form" class="dropzone" method="POST" action="/admin/illustrations/{{ $loggedInUser->id }}">
                            @csrf
                            <p class="mb-1">Title of Illustration</p>
                            <input type="text" name="title" placeholder="Enter the name of catagory" class="mb-2 form-control" required/>
                            @component('Master.formValidationError', ['param' => 'title'])
                            @endcomponent()
                            <p class="mb-1">Upload SVG</p>
                            <input type="file" name="svg" accept="image/svg+xml" required/>
                            @component('Master.formValidationError', ['param' => 'svg'])
                            @endcomponent()
                            <p class="mb-1">Illustration Catagory</p>
                            <select name="illustration_catagory_id" class="mb-2 form-control" required>
                                @foreach(\App\IllustrationCatagory::all() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @component('Master.formValidationError', ['param' => 'illustration_catagory_id'])
                            @endcomponent()

                            <h4>Image Keywords</h4>
                            <p class="mb-1">Please enter 5 keywords for the Illustration</p>
                            @for($i=0;$i<5;$i++)
                                <input type="text" name="keyword_{{ $i }}" placeholder="Enter the keyword {{ $i }}" class="mb-2 form-control"/>
                                @component('Master.formValidationError', ['param' => 'keyword_'.$i])
                                @endcomponent()
                            @endfor

                            <h4>Illustration Color Tags</h4>
                            <p class="mb-1">Please enter 6 color tag (svg element ids) for coloring. For your ease they have been already filled by prerefined value. Feel free to customize them.</p>
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" name="color_{{ $i }}" placeholder="Enter the color tag" class="mb-2 form-control" value=""/>
                                @component('Master.formValidationError', ['param' => 'color_'.$i])
                                @endcomponent()
                            @endfor


                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" role="button" onclick="document.getElementById('add-illustration-form').submit()">Save</button>
                    </div>
                </div>
            </div>
        </div>



        <br>
        <br>
        <v-modal name="Import from CSV" title="Import from CSV" button_title="Import from CSV">
            <div style="height:100px;overflow-y:auto">
                <form method="post" enctype="multipart/form-data" id="import-from-csv" class="mt-1" action="/admin/illustrations/csv/{{ $loggedInUser->id }}">
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
            title,svg,category,keyword 1,keyword 2,keyword 3,keyword 4,keyword 5,color 1,color 2,color 3,color 4,color 5,color 6
        </p>


        <div class="mt-5">

                @foreach(\App\Illustration::all() as $ill)
                    <va-card>
                        <div class="d-flex align-items-center">
                            <p class="m-0">{{ $loop->iteration }}.</p>
                            <div class="ml-3">
                                <p class="m-0">Title : {{ $ill->title }}</p>
                                <p class="m-0">Catagory : {{ $ill->IllustrationCatagory->name }}</p>
                                <p class="m-0">Color Slot ID :
                                    @foreach($ill->IllustrationColorSlot as $item)
                                        {{ $item->color_id }},&nbsp
                                    @endforeach
                                </p>
                                <p class="m-0">Keywords :
                                        @foreach($ill->IllustrationKeyword as $item)
                                            {{ $item->name }},&nbsp
                                        @endforeach
                                    </p>
                                <a href="{{ $ill->svg }}" target="_blank">View Illustration.</a>
                            </div>
                        </div>
                        <template v-slot:buttons>
                            <form action="/admin/illustrations/{{ $ill->id }}/{{ $loggedInUser->id }}" method="post" id="delete-ill-{{ $ill->id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button onclick="confirm('Are you sure you want to delete it?')? document.getElementById('delete-ill-{{  $ill->id }}').submit() : -1" class="btn btn-danger"><i class="fa fa-trash mr-2"></i>Delete</button>

                            <button class="ml-2 btn btn-warning" type="button" data-toggle="modal" data-target="#edit-illustration-modal-{{ $loop->iteration }}"><i class="fa fa-edit"></i>Edit Illustration</button>

                            <div role="dialog" tabindex="-1" class="modal fade" id="edit-illustration-modal-{{ $loop->iteration }}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form enctype="multipart/form-data" id="edit-illustration-form-{{ $loop->iteration }}" class="dropzone" method="POST" action="/admin/illustration/edit/{{ $ill->id }}/{{ $loggedInUser->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <p class="mb-1">Title of Illustration</p>
                                                <input value="{{ $ill->title }}"  type="text" name="title" placeholder="Enter the name of catagory" class="mb-2 form-control" />
                                                <p class="mb-1">Upload SVG</p>
                                                <input type="file" name="svg" accept="image/svg+xml" />
                                                <p class="mb-1">Illustration Catagory</p>
                                                <select name="illustration_catagory_id" class="mb-2 form-control" required>
                                                    @foreach(\App\IllustrationCatagory::all() as $cat)
                                                        <option value="{{ $cat->id }}" {{ $cat->id == $ill->IllustrationCatagory->id ? "selected" : ""}}>{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                                @component('Master.formValidationError', ['param' => 'illustration_catagory_id'])
                                                @endcomponent()

                                                <h4>Image Keywords</h4>
                                                <p class="mb-1">Please enter 5 keywords for the Illustration</p>
                                                @for($i=0;$i<5;$i++)
                                                    <input type="text" name="keyword_{{ $i }}" placeholder="Enter the keyword {{ $i }}" class="mb-2 form-control"
                                                        value="{{ count($ill->IllustrationKeyword) > $i ? $ill->IllustrationKeyword[$i]->name : ''}}"
                                                    />
                                                @endfor

                                                <h4>Illustration Color Tags</h4>
                                                <p class="mb-1">Please enter 6 color tag (svg element ids) for coloring. For your ease they have been already filled by prerefined value. Feel free to customize them.</p>
                                                @for($i = 0; $i < 6; $i++)
                                                    <input type="text" name="color_{{ $i }}" placeholder="Enter the color tag" class="mb-2 form-control" value="color-{{ $i }}"
                                                        value="{{ count($ill->IllustrationColorSlot) > $i ? $ill->IllustrationColorSlot[$i]->color_id : '' }}"
                                                    />
                                                @endfor


                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-light" type="button" data-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" role="button" onclick="document.getElementById('edit-illustration-form-{{ $loop->iteration }}').submit()">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </template>
                    </va-card>
                @endforeach

        </div>

    </div>




@endsection()
