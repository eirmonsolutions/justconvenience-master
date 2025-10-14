@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-md-1 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Store Coupon</h4>
                        <form action="{{ route('save-store-coupon') }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="row">
                                    @csrf
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="code" type="text" class="floating-label-field floating-label-field--s1 name" id="inputName" placeholder="Coupon Code*" value="{{ old('code') }}" required>
                                        <label for="inputName" class="floating-label">Coupon Code*</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 form-group">
                                        <label for="inputHeading" class="label-setting">Coupon Type*</label>
                                        <select name="type" id="selectCouponType" class="form-control" required>
                                            <option value="1">Fixed</option>
                                            <option value="2">Percentage</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="coupon_value" type="number" step="0.01" max="100" class="floating-label-field floating-label-field--s1 name" id="inputCouponValue" placeholder="Coupon Value*" value="{{ old('coupon_value') }}" required>
                                        <label for="inputCouponValue" class="floating-label">Coupon Value*</label>
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