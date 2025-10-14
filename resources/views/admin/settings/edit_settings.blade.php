@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 edit-settings form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Settings</h4>
                        <form action="{{ route('update-settings') }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        @csrf
                                        <label class="label-setting">Choose Primary Logo</label>
                                        <div class="custom-file">
                                            <input id="inputPrimaryLogo" type="file" name="primary_logo" accept="image/*"  class="form-control custom-file-input" />
                                            <label class="custom-file-label" for="inputPrimaryLogo">Choose file</label>

                                            <input type="hidden" name="old_primary_logo" value="{{ isset($data['homeData']['logo']['primary_logo']) ? $data['homeData']['logo']['primary_logo'] : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="inputHeading" class="label-setting"> Heading</label>
                                        <input name="heading" type="text" class="input-setting name" id="inputHeading" placeholder="Heading" value="{{ isset($data['homeData']['heading']) ? $data['homeData']['heading'] : '' }}" required>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="inputAddressLine" class="label-setting">Address Line</label>
                                        <input name="address_line" type="text" class="input-setting  name" id="inputAddressLine" placeholder="Address Line" value="{{ isset($data['homeData']['information']['address_line']) ? $data['homeData']['information']['address_line'] : '' }}" required>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="inputPhoneNumber" class="label-setting">Phone Number</label>
                                        <input name="phone_number" type="text" class="input-setting  name" id="inputPhoneNumber" placeholder="Phone Number" value="{{ isset($data['homeData']['information']['phone_number']) ? $data['homeData']['information']['phone_number'] : '' }}" required>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="inputEmail" class="label-setting">Email</label>
                                        <input name="email" type="email" class="input-setting  name" id="inputEmail" placeholder="Email" value="{{ isset($data['homeData']['information']['email']) ? $data['homeData']['information']['email'] : '' }}" required>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="inputSectionText2" class="label-setting">Footer Logo</label>
                                        <div class="custom-file">
                                            <input id="inputSecondaryLogo" type="file" name="secondary_logo" accept="image/*"  class="form-control custom-file-input" />
                                            <label class="custom-file-label" for="inputSecondaryLogo">Choose Logo</label>

                                            <input type="hidden" name="old_secondary_logo" value="{{ isset($data['homeData']['logo']['secondary_logo']) ? $data['homeData']['logo']['secondary_logo'] : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="inputWelcomeEmailText" class="label-setting">Welcome Email Text</label>
                                        <textarea class="form-control mail_message_box" name="welcome_email_text" required>{{ isset($data['homeData']['email_template']['welcome_email_text']) ? $data['homeData']['email_template']['welcome_email_text'] : '' }}</textarea>
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