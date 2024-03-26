@extends('layout.admin.master')
@section('css')
    <style>
        .avatar-picture {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            margin-bottom: 33px;
        }

        .avatar-picture .image-input {
            position: relative;
            display: inline-block;
            border-radius: 50%;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .avatar-picture .image-input .image-input-wrapper {
            border: 3px solid #fff;
            background-image: url("");
            width: 300px;
            height: 300px;
            /* border-radius: 50%; */
            background-repeat: no-repeat;
            background-size: contain !important;
        }

        .avatar-picture .image-input .btn {
            height: 24px;
            width: 24px;
            border-radius: 50%;
            cursor: pointer;
            position: absolute;
            left: 3px;
            bottom: -7px;
            background-color: #FFFFFF;
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            padding: 0;
            -webkit-filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.16));
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.16));
        }

        .avatar-picture .image-input .btn img {
            position: relative;
            top: -2px;
        }

        .avatar-picture .image-input .btn:hover {
            background-color: var(--main-color);
        }

        .avatar-picture .image-input .btn:hover img {
            -webkit-filter: invert(1) brightness(10);
            filter: invert(1) brightness(10);
        }

        .avatar-picture .image-input .btn input {
            width: 0 !important;
            height: 0 !important;
            overflow: hidden;
            opacity: 0;
            display: none;
        }
    </style>
@endsection
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Linked <b>BOL</b> Accounts</h6>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#account-create-update-modal" data-modal-title="Link New Bol Account" data-is-create="1"
                    data-action="{{ route('bol_accounts.store') }}" data-method="POST"
                    data-image="{{ asset('assets/img/product-placeholder.webp') }}"><i class="fa fa-plus"></i>&nbsp;NEW</button>
            </div>
            <div class="table-responsive">
                <form action="" id="table-form" method="POST">
                    @csrf
                    <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col"><input class="form-check-input" type="checkbox" id="check_all"></th>
                                <th scope="col">Logo</th>
                                <th scope="col">Name</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Action</th>
                                {{-- <th scope="col">Date</th> --}}
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
    @include('admin.accounts.create-update-modal')
@endsection


@section('js')
    <script>
        var api_data_url = "{{ $ajax_route }}";
    </script>
    <script src="{{ asset('assets/js/custom/accounts.js?v=0.2') }}"></script>
@endsection
