@extends('layout.admin.master')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Orders</h6>
                <button type="button" class="btn btn-outline-primary btn-pdf"><i class="fa fa-print"></i></button>
            </div>
            <div class="table-responsive">
                <form action="" id="filters-form" method="GET">
                    @include('admin.partials.filters', ['bol_accounts' => $bol_accounts , 'form_name' => 'filters-form'])
                </form>
                <form action="" id="table-form" method="GET">
                    @csrf
                    <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col"><input class="form-check-input" type="checkbox" id="check_all"></th>
                                <th scope="col">Image</th>
                                <th scope="col">Order No</th>
                                <th scope="col">Account</th>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Place Date</th>
                                <th scope="col">Price</th>
                                <th scope="col">Country</th>
                                <th scope="col">Total</th>
                                {{-- <th scope="col">Action</th> --}}
                                {{-- <th scope="col">Date</th> --}}
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        var api_data_url = "{{ $ajax_route }}";
    </script>
    <script src="{{ asset('assets/js/custom/order.js?v=0.09') }}"></script>
@endsection
