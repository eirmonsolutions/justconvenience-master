@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 offset-md-2 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Contest - {{ $data->title }}</h4>
                        <form action="{{ route('update-contest') }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $data->id }}">

                                        <input name="title" type="text" class="floating-label-field floating-label-field--s1 name" id="inputTitle" placeholder="Title*" value="{{ $data->title }}" required>
                                        <label for="" class="floating-label">Title*</label>
                                    </div>


                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <textarea class="floating-label-field floating-label-field--s1 name" name="description" id="inputDescription" rows="3" placeholder="Description*" required>{{ $data->description }}</textarea>
                                        <label for="" class="floating-label">Description*</label>
                                   </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input type="text" class="date form-control floating-label-field floating-label-field--s1 name" name="start_date" placeholder="Start Date*" required autocomplete="off" value="{{ date('d M Y', strtotime($data->start_date)) }}">
                                        <label for="" class="floating-label">Start Date*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input type="text" class="date form-control floating-label-field floating-label-field--s1 name" name="end_date" placeholder="End Date*" required autocomplete="off" value="{{ date('d M Y', strtotime($data->end_date)) }}">
                                        <label for="" class="floating-label">End Date*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <div class="custom-file">
                                            <input id="inputGroupFile04" type="file" name="featured_image" accept="image/*"  class="form-control custom-file-input" />
                                            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                            
                                            <img class="u-img" src="{{ url('/') . '/' . $data->featured_image }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="text-right MT30">
                                    <button type="submit" class="btn btn-info">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection