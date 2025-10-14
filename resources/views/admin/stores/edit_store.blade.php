@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-md-1 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Store</h4>
                        <form action="{{ route('update-store') }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="row">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $data->id }}">

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputName" placeholder="Name*" value="{{ $data->name }}" required @if(Auth::user()->user_role != 2) readonly @endif>
                                        <label for="inputName" class="floating-label">Name*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="phone_number" type="text" class="floating-label-field floating-label-field--s1 name" id="inputPhoneNumber" placeholder="Phone Number*" value="{{ $data->phone_number }}" required @if(Auth::user()->user_role != 2) readonly @endif>
                                        <label for="inputPhoneNumber" class="floating-label">Phone Number*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="email" type="email" class="floating-label-field floating-label-field--s1 name" id="inputEmail" placeholder="Email*" value="{{ $data->email }}" required @if(Auth::user()->user_role != 2) readonly @endif>
                                        <label for="inputEmail" class="floating-label">Email*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="store_name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputStoreName" placeholder="Store Name*" value="{{ $data->store_name }}" required @if(Auth::user()->user_role != 2) readonly @endif>
                                        <label for="inputStoreName" class="floating-label">Store Name*</label>
                                    </div>

                                    @if(Auth::user()->user_role == 2)
                                        <div class="col-lg-12 col-md-12 floating-label-wrap">
                                            <div class="custom-file">
                                                <input id="inputGroupFile04" type="file" name="image" accept="image/*"  class="form-control custom-file-input" />
                                                <label class="custom-file-label" for="inputGroupFile04">Choose Store Image</label>
                                            </div>
                                        </div>

                                        @if($data->image)
                                            <div class="col-lg-12 col-md-12 text-center">
                                                <img class="u-img" src="{{ url('/') . '/' . $data->image }}"/>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                       <textarea class="floating-label-field floating-label-field--s1 name" name="address" id="inputStoreAddress" rows="3" placeholder="Store Address*" required  @if(Auth::user()->user_role != 2) readonly @endif>{{ $data->address }}</textarea>
                                       <label for="inputStoreAddress" class="floating-label">Store Address*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="city" type="text" class="floating-label-field floating-label-field--s1 name" id="inputStoreCity" placeholder="Store City/Town*" value="{{ $data->city }}" required  @if(Auth::user()->user_role != 2) readonly @endif>
                                        <label for="inputStoreCity" class="floating-label">Store City/Town*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="state" type="text" class="floating-label-field floating-label-field--s1 name" id="inputStoreState" placeholder="Store County*" value="{{ $data->state }}" required  @if(Auth::user()->user_role != 2) readonly @endif>
                                        <label for="inputStoreState" class="floating-label">Store County*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="country" type="text" class="floating-label-field floating-label-field--s1 name" id="inputStoreCountry" placeholder="Store Country*" value="{{ $data->country }}" required @if(Auth::user()->user_role != 2) readonly @endif>
                                        <label for="inputStoreCountry" class="floating-label">Store Country*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="zipcode" type="text" class="floating-label-field floating-label-field--s1 name" id="inputStoreZipcode" placeholder="Store Postcode*" value="{{ $data->zipcode }}" required @if(Auth::user()->user_role != 2) readonly @endif>
                                        <label for="inputStoreZipcode" class="floating-label">Store Postcode*</label>
                                    </div>

                                    @if(Auth::user()->user_role == 2)
                                        <div class="col-lg-12 col-md-12 floating-label-wrap">
                                            <label for="exampleFormControlSelect1">Delivery Service</label>
                                            <select class="form-control mb-3 delivery_service" name="delivery_service" id="exampleFormControlSelect1">
                                                <option @if($data->delivery_service == 1) selected @endif value="1">Yes</option>
                                                <option @if($data->delivery_service == 0) selected @endif value="0">No</option>
                                            </select>
                                        </div>

                                        <div class="col-12 delivery_div @if($data->delivery_service == 0) d-none @endif">
                                            <div class="col-lg-12 col-md-12 floating-label-wrap">
                                                <input name="minimum_order_amount" type="number" class="floating-label-field floating-label-field--s1 name minimum_order_amount" id="inputMinimumOrderAmount" placeholder="Minimum Order Amount*" value="{{ $data->minimum_order_amount }}" required>
                                                <label for="inputMinimumOrderAmount" class="floating-label">Minimum Order Amount*</label>
                                            </div>

                                            <div class="col-lg-12 col-md-12 floating-label-wrap">
                                                <input name="delivery_charges" type="number" class="floating-label-field floating-label-field--s1 name delivery_charges" id="inputDeliveryCharges" placeholder="Delivery Charges*" value="{{ $data->delivery_charges }}" required>
                                                <label for="inputDeliveryCharges" class="floating-label">Delivery Charges*</label>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 floating-label-wrap">
                                            <label for="exampleStoreOpeningStatus">Store Opening Status</label>
                                            <select class="form-control mb-3" name="store_opening_status" id="exampleStoreOpeningStatus">
                                                <option @if($data->store_opening_status == 1) selected @endif value="1">Yes</option>
                                                <option @if($data->store_opening_status == 0) selected @endif value="0">No</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-12 col-md-12 floating-label-wrap">
                                            <label for="exampleIsStorePaid">Store Paid</label>
                                            <select class="form-control mb-3" name="is_store_paid" id="exampleIsStorePaid">
                                                <option @if($data->is_store_paid == 1) selected @endif value="1">Yes</option>
                                                <option @if($data->is_store_paid == 0) selected @endif value="0">No</option>
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="merchantID" type="text" class="floating-label-field floating-label-field--s1 name" id="inputmerchantID" placeholder="Merchant ID" value="{{ $data->merchantID }}">
                                        <label for="inputmerchantID" class="floating-label">Merchant ID*</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="merchantSecret" type="text" class="floating-label-field floating-label-field--s1 name" id="inputmerchantSecret" placeholder="Merchant Secret" value="{{ $data->merchantSecret }}">
                                        <label for="inputmerchantSecret" class="floating-label">Merchant Secret*</label>
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

@push('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            $('.delivery_service').on('change', function()
            {
                var currentElement = $(this);
                var selectedValue = currentElement.val();
                console.log(selectedValue);
                if(selectedValue == 1)
                {
                    $('.delivery_div').removeClass('d-none');

                    $('.minimum_order_amount').attr('required', true);   
                    $('.delivery_charges').attr('required', true);   
                }
                else
                {
                    $('.delivery_div').addClass('d-none');   
                    
                    $('.minimum_order_amount').attr('required', false);   
                    $('.delivery_charges').attr('required', false);   
                }
            });
        });
    </script>
@endpush