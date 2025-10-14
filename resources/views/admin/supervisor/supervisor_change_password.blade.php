@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 offset-md-2 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Change Password</h4>
                        <form action="{{ route('supervisor-change-password', ['id' => $id]) }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body"> 
                                
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        @csrf
                                        <input name="password" type="password" class="floating-label-field floating-label-field--s1 name" id="inputPassword" placeholder="Password" required> 
                                        <label for="inputPassword" class="floating-label">Password*</label>
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