@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 offset-md-2 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Supervisor</h4>
                        <form action="{{ route('save-supervisor') }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        @csrf
                                         <input name="name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputName" placeholder="Name*" value="{{ old('name') }}" required>
                                       <!--  <input name="shop_name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputTitle" placeholder="Shop Name*" value="{{ old('name') }}" required> -->
                                        <label for="inputName" class="floating-label">Name*</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                         <input name="last_name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputLastName" placeholder="Last Name*" value="{{ old('last_name') }}" required>
                                        <label for="inputLastName" class="floating-label">Last Name*</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="email" type="email" class="floating-label-field floating-label-field--s1 name" id="inputEmail" placeholder="Email" value="{{ old('email') }}" required>
                                       <!--  <input name="contract_number" type="text" class="floating-label-field floating-label-field--s1 name" id="inputContractNumber" placeholder="Contract Number*" value="{{ old('contract_number') }}" required> -->
                                        <label for="inputEmail" class="floating-label">Email*</label>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="password" type="password" class="floating-label-field floating-label-field--s1 name" id="inputPassword" placeholder="Password" required>
                                      <!--   <input name="shopping_center_name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputShoppingCenterName" placeholder="Shopping Center Name*" value="{{ old('shopping_center_name') }}" required> -->
                                        <label for="inputPassword" class="floating-label">Password*</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="text-right MT30">
                                    <button type="submit" class="btn btn-info">Save</button>
                                </div>
                            </div>
                                    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection