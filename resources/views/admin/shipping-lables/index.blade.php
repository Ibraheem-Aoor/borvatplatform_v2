@extends('layout.admin.master')
@section('css')
    <style>
        th,
        td {
            font-size: 12px !important;
        }
    </style>
@endsection
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">

        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Shipping Lables</h6>
                <button type="button" class="btn btn-primary" onclick="$('#shipment-form').submit();"><i
                        class="fa fa-print"></i></button>
            </div>
            <div class="table-responsive">
                <div class="row">
                    <div class="col-sm-4">
                        <select name="shipping_api" class="form-control mb-2">
                            <option value="1">Myparcel labels</option>
                            <option value="2">Sendy labels</option>
                            <option value="3">Pro Parcel</option>
                        </select>
                    </div>
                </div>
                <form id="shipment-form" action="{{route('shippment.print-myParcel-lables')}}" method="POST">
                    @csrf
                    <input type="text" hidden name="src" value="BOL-LABELS">
                    <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col"><input class="form-check-input" type="checkbox" id="check_all"></th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Shipment No</th>
                                <th scope="col">Ref</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Sure Name</th>
                                <th scope="col">City</th>
                                <th scope="col">Street Name</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                    </table>
                </form>

            </div>
        </div>

    </div>
    <!-- Recent Sales End -->
@endsection

@section('js')
    <script>
        var myParcelAjax = sendyAjax = null;
        var printMyParcelabelsUrl = "{{ route('shippment.print-myParcel-lables') }}";
        var printSendyLabelsUrl = "{{ route('shippment.print-sendy-lables') }}";
        var printProParcelLabelsUrl = "{{ route('shippment.print-proParcel-lables') }}";
        @if (Route::currentRouteName() == 'shippment.bol.shipping-labels')
            myParcelAjax = "{{ route('shippment.labels.data', ['type' => 'BOL', 'shipping_api' => 1]) }} ";
            sendyAjax = "{{ route('shippment.labels.data', ['type' => 'BOL', 'shipping_api' => 2]) }} ";
            proParcelAjax = "{{ route('shippment.labels.data', ['type' => 'BOL', 'shipping_api' => 3]) }} ";
        @else
            myParcelAjax = "{{ route('shippment.labels.data', ['type' => 'BORVAT', 'shipping_api' => 1]) }}";
            sendyAjax = "{{ route('shippment.labels.data', ['type' => 'BORVAT', 'shipping_api' => 2]) }}";
            proParcelAjax = "{{ route('shippment.labels.data', ['type' => 'BORVAT', 'shipping_api' => 3]) }}";
        @endif
    </script>
    <script src="{{asset('assets/js/custom/shipping-label.js?v=0.01')}}"></script>
    <script>
        $(document).on('click', '#full-label', function() {
            $('#shipment-form').attr('action', '{{ route('shippment.full-pdf') }}');
            $('#shipment-form').submit();
        });
    </script>
@endsection
