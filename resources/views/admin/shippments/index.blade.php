@extends('layout.admin.master')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Shipments</h6>
                <br>
                <button type="button" class="btn btn-success btn-md" id="full-label"><i class="fa fa-print"></i>LABEL</button>
                &nbsp;
                <button type="button" class="btn btn-secondary btn-md " id="excel-btn"><i
                        class="fa fa-print"></i>EXCEL</button>
                @if (Route::currentRouteName() == 'shippment.index')
                    &nbsp;
                    <button type="button" class="btn btn-success btn-sm" id="full-label-copy"><i
                            class="fa fa-copy"></i></button>
                @endif
                {{-- <button type="button" class="btn btn-primary" onclick="$('#shipment-form').submit();"><i
                        class="fa fa-print"></i></button> --}}
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <form name="search-from" action="{{ route('shippment.search') }}" method="GET">
                    @csrf
                    <div class="d-flex">
                        <input type="date" name="from_date" class="form-control" min="2023-01-01"
                            value="{{ $from_date ?? \Carbon\Carbon::today()->toDateString() }}">
                        &nbsp;
                        <i class="fa fa-arrow-right mt-2"></i>
                        &nbsp;
                        <input type="date" name="to_date" class="form-control" min="2023-01-01"
                            value="{{ $to_date ?? \Carbon\Carbon::tomorrow()->toDateString() }}">
                        <button type="submit" class="btn-xs btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                </form>
                <div class="col-sm-12">
                    <h4>TODAY'S SHIPMENTS @if (isset($current_account_name))
                            for {{ $current_account_name }}
                        @endif
                    </h4>
                    <h3 class="text-danger">{{ $today_shipments_count }}</h3>
                </div>
            </div>
            <div class="table-responsive">
                <form action="" id="filters-form" method="GET">
                    @include('admin.partials.filters', [
                        'bol_accounts' => $bol_accounts,
                        'form_name' => 'filters-form',
                    ])
                </form>
                <form id="shipment-form" action="{{ $form_route }}" method="POST">
                    @csrf
                    <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col"><input class="form-check-input" type="checkbox" id="check_all"></th>
                                @if (Route::currentRouteName() != 'shippment.borvat.index')
                                    <th scope="col">Image</th>
                                @endif
                                <th scope="col">Order ID</th>
                                <th scope="col">Shipment No</th>
                                <th scope="col">Account</th>
                                <th scope="col">Label</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Sure Name</th>
                                <th scope="col">Country</th>
                                <th scope="col">Place Date</th>
                                <th scope="col">Is Printed</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>


    </div>
    <!-- Recent Sales End -->

    @include('admin.partials.edit-shipment-modal')
@endsection

@section('js')
    <script>
        $('#editShipmentModal').on('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            var headerText = btn.getAttribute('data-header-title');
            var shipmentId = btn.getAttribute('data-id');
            var shipmentNote = btn.getAttribute('data-note');
            $('#editShipmentHeader').text(headerText);
            $(this).find('input[name="id"]').val(shipmentId);
            $(this).find('textarea').val(shipmentNote);
        });
    </script>
    {{-- Handle Shipment Note Form Ajax --}}
    <script>
        $(document).on('submit', 'form[name="note-form"]', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status && response.is_stored) {
                        toastr.success(response.message);
                        $('#row-' + response.row).text(response.note);
                        $('button[data-id="' + response.row + '"]').attr('data-note', response.note);
                        $('#editShipmentModal').modal('hide');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(response) {
                    toastr.error(response.message);
                }
            });
        });
    </script>
    <script>
        $(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! $table_data_url !!}",
                order: [
                    [
                        9,
                        'desc',

                    ]
                ],
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'image',
                        name: 'image',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'order_id',
                        name: 'order.api_id',
                        searchable: true,
                        orderable: false,
                    },
                    {
                        data: 'api_id',
                        name: 'api_id',
                    },
                    {
                        data: 'account',
                        name: 'account',
                        searchable: true,
                    },
                    {
                        data: 'label',
                        name: 'label',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'firstName',
                        name: 'first_name',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'surname',
                        name: 'surname',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'country_code',
                        name: 'country_code',
                        orderable: true,
                        searchable: true,

                    },

                    {
                        data: 'place_date',
                        name: 'place_date',
                    },
                    {
                        data: 'is_printed',
                        name: 'is_printed',
                    },
                ]
            });
        });
    </script>

    <script>
        $(document).on('click', '#full-label', function() {
            $('#shipment-form').attr('action', '{{ $full_pdf_route }}');
            $('#shipment-form').submit();
        });
        $(document).on('click', '#excel-btn', function() {
            $('#shipment-form').attr('action', '{{ $full_excel_route }}');
            $('#shipment-form').submit();
        });
        @if (Route::currentRouteName() == 'shippment.index')
            $(document).on('click', '#full-label-copy', function() {
                $('#shipment-form').attr('action', '{{ $full_pdf_copy_route }}');
                $('#shipment-form').submit();
            });
        @endif
    </script>
@endsection
