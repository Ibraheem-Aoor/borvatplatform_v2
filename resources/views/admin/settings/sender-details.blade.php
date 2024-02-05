@extends('layout.admin.master')
@section('content')
    {{-- Start content --}}
    <div class="container-fluid pt-4 px-4">
        <div class="row vh-100 bg-light rounded  justify-content-center mx-0">
            <div class="col-sm-12 col-xl-6">
                <form id="sender-details-form" action="{{ route('settings.sender-details.update') }}">
                    <div class="bg-light rounded h-100 p-4">
                        <h6 class="mb-4">ORDER SENDER DETAILS</h6>
                        <div class="form-floating mb-3">
                            <input type="text" name="company" class="form-control" id="floatingInput" value="{{$shipment_sender_details['company']}}">
                            <label for="floatingInput">Company Name:</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="street_and_house" class="form-control" id="floatingPassword" value="{{$shipment_sender_details['street_and_house']}}">
                            <label for="floatingPassword">Street & House No.</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="city_and_zip" class="form-control" id="floatingPassword" value="{{$shipment_sender_details['city_and_zip']}}">
                            <label for="floatingPassword">City & Zip Code.</label>
                        </div>
                        <button type="submit" class="btn btn-success">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End content --}}
@endsection


@section('js')
    <script>
        $(document).on('submit', 'form', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status && response.is_updated) {
                        toastr.success(response.message);
                    } else {
                        toastr.error('something went wrong');
                    }
                },
                error: function(response) {
                    if (response.status == 422) {
                        console.log(response);
                        $.each(response.responseJSON.errors, function(key, item) {
                            toastr.error(item);
                        });
                    }
                },
            });
        })
    </script>
@endsection
