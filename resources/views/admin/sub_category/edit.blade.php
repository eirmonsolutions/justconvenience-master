@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-md-1 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Sub Category</h4>
                        <form action="{{ route('update-sub-category') }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="row">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $data->id }}">

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputName" placeholder="Name*" value="{{ $data->name }}" required>
                                        <label for="inputName" class="floating-label">Name*</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="exampleFormControlSelect1">Category</label>
                                        <select class="form-control mb-3" name="category_id" id="exampleFormControlSelect1" required>
                                            @foreach ($categories as $key => $category)
                                                <option @if($category->id == $data->category_id) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <div class="custom-file">
                                            <input id="inputGroupFile04" type="file" name="featured_image" accept="image/*"  class="form-control custom-file-input" />
                                            <label class="custom-file-label" for="inputGroupFile04">Choose Category Image</label>
                                        </div>
                                    </div>

                                    @if($data->featured_image)
                                        <div class="col-lg-12 col-md-12 text-center">
                                            <img class="u-img" src="{{ url('/') . '/' . $data->featured_image }}"/>
                                        </div>
                                    @endif
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