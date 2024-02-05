@extends('layout.admin.master')
@section('content')
    <!-- Sales Chart Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0"><span class="text-danger">Top</span> 20 Products</h6>
                        {{-- <a href="">Show All</a> --}}
                    </div>
                    <canvas id="worldwide-sales"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Sales Chart End -->

    {{-- Top Products Hiddend Input For Js --}}


@endsection

@section('js')
    <script>
        var ctx1 = $("#worldwide-sales").get(0).getContext("2d");
        var myChart1 = new Chart(ctx1, {
            type: "bar",
            data: {
                labels: {!! $top_products_eans !!},
                datasets: [{
                    label: "SALES",
                    data: {!! $top_products_sales !!},
                    backgroundColor: "rgba(0, 156, 255, .7)"
                }, ]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
