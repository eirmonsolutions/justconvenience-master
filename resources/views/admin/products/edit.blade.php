@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-md-1 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Product</h4>
                        <form action="{{ route('update-product') }}" method="POST" id="Login" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="row">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $data->id }}">

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="name" type="text" class="floating-label-field floating-label-field--s1 name" id="inputName" placeholder="Name*" value="{{ $data->name }}" required>
                                        <label for="inputName" class="floating-label">Name*</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="bar_code" type="text" class="floating-label-field floating-label-field--s1 name" id="inputName" placeholder="Bar Code*" value="{{ $data->bar_code }}" required>
                                        <label for="inputName" class="floating-label">Bar Code*</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <input name="price" type="text" class="floating-label-field floating-label-field--s1 name decimal_format" id="inputPrice" placeholder="Price*" value="{{ ($data->price) ? $data->price : '' }}" required>
                                        <label for="inputPrice" class="floating-label">Price*</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <textarea class="floating-label-field floating-label-field--s1 name" name="description" id="inputStoreAddress" rows="3" placeholder="Description">{{ $data->description }}</textarea>
                                        <label for="inputStoreAddress" class="floating-label">Description</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="categories">Category*</label>
                                        <select class="form-control mb-3" name="category_id"  id="categories" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $key => $category)
                                                <option @if($category->id == $data->category_id) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <label for="subcategories">Sub Category</label>
                                        <select class="form-control mb-3 subcategories" name="subcategory_id" id="subcategories" required>
                                            <option value="">Select Subcategory</option>
                                            @foreach ($sub_categories as $key => $sub_category)
                                                <option @if($sub_category->id == $data->subcategory_id) selected @endif value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                        <div class="custom-file">
                                            <input id="inputGroupFile04" type="file" name="featured_image" accept="image/*"  class="form-control custom-file-input" />
                                            <label class="custom-file-label" for="inputGroupFile04">Choose Featured Image</label>
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

@push('scripts')
    <script type="text/javascript">
        $(document).on('change', '#categories', function () {
            var category_id = $(this).children("option:selected").val();
            $.ajax({
                url:"{{ url('') }}/get-sub-categories/" + category_id,
                method:"GET",
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    if (data.status == 1)
                    {
                        $("#subcategories").empty();
                        $('#subcategories').append('<option value="">Select Subcategory</option>');

                        $.each(data.sub_categories, function(key, value) 
                        {
                            $('#subcategories').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                    }
                    else
                    {
                        window.location.reload();
                        toastr.error(data.message);
                    }
                }
            }).fail(function (jqXHR, textStatus, error) {
                // window.location.reload();
                toastr.error('Something went wrong');
            });
        });
    </script>
@endpush