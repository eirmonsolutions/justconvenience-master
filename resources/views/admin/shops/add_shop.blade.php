@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 offset-md-2 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Shop</h4>
                        <form action="{{ route('save-shop') }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        @csrf
                                        <input name="shop_name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputTitle" placeholder="Shop Name*" value="{{ old('shop_name') }}" required>
                                        <label for="" class="floating-label">Shop Name*</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="contract_number" type="text" class="floating-label-field floating-label-field--s1 name" id="inputContractNumber" placeholder="Contract Number*" value="{{ old('contract_number') }}" required>
                                        <label for="" class="floating-label">Contract Number*</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="shopping_center_id" type="text" class="floating-label-field floating-label-field--s1 name" id="inputShoppingCenterID" placeholder="Shopping Center ID*" value="{{ old('shopping_center_id') }}" required>
                                        <label for="" class="floating-label">Shopping Center ID*</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="shopping_center_name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputShoppingCenterName" placeholder="Shopping Center Name*" value="{{ old('shopping_center_name') }}" required>
                                        <label for="" class="floating-label">Shopping Center Name*</label>
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