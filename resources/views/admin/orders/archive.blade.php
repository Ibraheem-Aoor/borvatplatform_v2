@extends('layout.admin.master')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Orders</h6>
                {{-- <button type="button" class="btn btn-primary" onclick="$('#ff').submit();"><i class="fa fa-print"></i></button> --}}
            </div>
            <div class="table-responsive">
                <form action="{{ $form_route }}" id="ff" method="POST">
                    @csrf
                    <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col"><input class="form-check-input" type="checkbox" id="check_all"></th>
                                <th scope="col">Order No</th>
                                <th scope="col">Image</th>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Place Date</th>
                                <th scope="col">Price</th>
                                <th scope="col">Country</th>
                                <th scope="col">Total</th>
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
        var ajaxRoute = "{{ $ajax_route }}";
        var current_orders_type = "{{ $current_orders_type }}";
        let columns = current_orders_type == 'bol' ? getBolOrderColumns() : getBorvatOrderColumns();

        function getBolOrderColumns() {
            return [{
                    data: 'checkbox',
                    name: 'checkbox',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'api_id',
                    name: 'api_id',
                },
                {
                    data: 'image',
                    name: 'image',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'title',
                    name: 'product.title',
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'place_date',
                    name: 'place_date',
                },
                {
                    data: 'unit_price',
                    name: 'unitPrice',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'country',
                    name: 'countryCode',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'total',
                    name: 'total',
                    searchable: false,
                    orderable: false,
                },
            ];
        }

        function getBorvatOrderColumns() {
            return [{
                    data: 'checkbox',
                    name: 'checkbox',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'api_id',
                    name: 'code',
                },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'title',
                    name: 'product.title',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'place_date',
                    name: 'place_date',
                    orderable: true,
                    searchable: true,

                },
                {
                    data: 'unit_price',
                    name: 'unitPrice',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'country',
                    name: 'country',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'total',
                    name: 'total',
                    orderable: false,
                    searchable: false,
                },
            ];
        }

        $(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: ajaxRoute,
                columns: columns,
            });
        });
    </script>
@endsection
