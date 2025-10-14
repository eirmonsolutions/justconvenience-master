@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 edit-settings form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Terms & Conditions</h4>
                        <form action="{{ route('update-tnc') }}" method="POST" id="Login" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <textarea name="meta_value" id="meta_value" rows="10" cols="80">{{ isset($data['tnc']) ? $data['tnc'] : '' }}</textarea>
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

    <script>
        CKEDITOR.replace( 'meta_value' );
    </script>

@endsection