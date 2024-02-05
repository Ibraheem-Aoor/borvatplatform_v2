@extends('layout.admin.master')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">

        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Shipments</h6>
                <button type="button" class="btn btn-primary" onclick="$('#shipment-form').submit();"><i
                        class="fa fa-print"></i></button>
            </div>
            <div class="table-responsive">
                <form id="shipment-form"  action="{{ route('shippment.pdf') }}" method="POST">
                    @csrf
                    <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col"><input class="form-check-input" type="checkbox" id="check_all"></th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Shipment No</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Sure Name</th>
                                <th scope="col">City</th>
                                <th scope="col">Street Name</th>
                                <th scope="col">House Number</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shippments as $shippment)
                                <tr>
                                    <td><input class="form-check-input" type="checkbox" name="id[]"
                                            value="{{ $shippment->id }}"></td>
                                    <td>{{ $shippment->order->api_id }}</td>
                                    <td>{{ $shippment->api_id }}</td>
                                    @php
                                        $shipment_details = json_decode($shippment->shipment_details, true);
                                    @endphp
                                    <td>
                                        {{ $shipment_details['firstName'] }}
                                    </td>
                                    <td>
                                        {{ $shipment_details['surname'] }}
                                    </td>
                                    <td>
                                        {{ $shipment_details['city'] }}
                                    </td>
                                    <td>
                                        {{ $shipment_details['streetName'] }}
                                    </td>
                                    <td>
                                        {{ $shipment_details['houseNumber'] }}
                                    </td>
                                    <td>{{ $shippment->place_date }}</td>

                                    {{-- <td><a class="btn btn-sm btn-primary" href="">Detail</a></td> --}}
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </form>

            </div>
        </div>

    </div>
    <!-- Recent Sales End -->
@endsection

