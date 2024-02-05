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

        th,
        td {
            font-size: 14px !important;
        }
    </style>
@endsection
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">All Products</h6>
                {{-- <a type="button" class="btn btn-primary" href="{{ route('order.pdf') }}"><i class="fa fa-print"></i></a> --}}
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            {{-- <th scope="col"><input class="form-check-input" type="checkbox" id="check_all"></th> --}}
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">EAN</th>
                            <th scope="col">Number Of Pieces</th>
                            <th scope="col">Purchase Place</th>
                            <th scope="col">Purchase Price</th>
                            <th scope="col">Weight</th>
                            <th scope="col">Width</th>
                            <th scope="col">Height</th>
                            <th scope="col">Length</th>
                            <th scope="col">Note</th>
                            <th scope="col">Content</th>
                            <th scope="col">Action</th>
                            {{-- <th scope="col">Date</th> --}}
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->ean }}</td>
                                <td><img id="{{ $product->id }}"
                                        src="@if ($product->image) {{ Storage::url('products/' . $product->id . '/' . $product->image) }} @else {{ asset('assets/img/product-placeholder.webp') }} @endif"
                                        width="300" alt=""></td>
                                <td><button id="btn-{{ $product->id }}" type="button" class="btn-sm btn btn-success"
                                        data-id="{{ $product->id }}" data-title="{{ $product->title }}"
                                        @if ($product->image) data-image="{{ Storage::url('products/' . $product->id . '/' . $product->image) }}" @endif
                                        data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                            class="fa fa-edit"></i></button></td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->

    {{-- Start product edit  Modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('product.edit') }}" id="edit-product-form">
                        <div class="avatar-picture">
                            <div class="image-input image-input-outline" id="imgUserProfile">
                                <div class="image-input-wrapper"
                                    style="background-image: url('{{ asset('assets/img/product-placeholder.webp') }}');">
                                </div>

                                <label class="btn">
                                    <i>
                                        <img src="{{ asset('assets/img/edit.svg') }}" alt="" class="img-fluid">
                                    </i>
                                    <input type="file" name="product_image" id="changeImg" accept=".png, .jpg, .jpeg">
                                    <input type="button" value="Upload" id="uploadButton">
                                </label>

                            </div>
                        </div>
                        <input type="hidden" name="product_id">
                        <div class="mb-2">
                            <label for="">Content</label>
                            <textarea name="content" id="content" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-2">
                            <label for="">Note</label>
                            <textarea name="note" id="note" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="number_of_pieces" class="form-control"
                                placeholder="Number Of Pieces">
                        </div>
                        <div class="mb-2">
                            <input type="text" name="purchase_place" class="form-control" placeholder="purchase place">
                        </div>
                        <div class="mb-2">
                            <input type="number" name="purchase_price" class="form-control"
                                placeholder="purchase price (EUR)">
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="">Weight</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Width</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="">lenth</label>

                            </div>
                            <div class="col-sm-3">
                                <label for="">Height</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="number" name="weight" class="form-control" placeholder="gm ">
                            </div>
                            <div class="col-sm-3">
                                <input type="number" name="width" class="form-control" placeholder="cm ">
                            </div>
                            <div class="col-sm-3">
                                <input type="number" name="length" class="form-control" placeholder="cm ">
                            </div>
                            <div class="col-sm-3">
                                <input type="number" name="height" class="form-control" placeholder="cm">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="event.preventDefault();$('#edit-product-form').submit();">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End Product Edit Modal --}}
@endsection


@section('js')
    <script>
        $(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! Route::currentRouteName() == 'product.index' ? route('product.get-all') : route('product.get-no-image') !!}',
                columns: [{
                        data: 'image',
                        name: 'image',
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'ean',
                        name: 'ean',
                    },
                    {
                        data: 'number_of_pieces',
                        name: 'number_of_pieces',
                    },
                    {
                        data: 'purchase_place',
                        name: 'purchase_place',
                    },
                    {
                        data: 'purchase_price',
                        name: 'purchase_price',
                    },
                    {
                        data: 'weight',
                        name: 'weight',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'width',
                        name: 'width',
                    },
                    {
                        data: 'height',
                        name: 'height',
                    },
                    {
                        data: 'length',
                        name: 'length',
                    },
                    {
                        data: 'note',
                        name: 'note',
                    },
                    {
                        data: 'content',
                        name: 'content',
                    },

                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false,
                    }
                ]
            });
        });
    </script>

    <script>
        // change image and preveiw
        $('#uploadButton').on('click', function() {
            $('#changeImg').click();
        })

        $('#changeImg').change(function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                $('.image-input-wrapper').css('background-image', 'url("' + reader.result + '")');
            }
            if (file) {
                reader.readAsDataURL(file);
            }
        });

        $('#exampleModal').on('shown.bs.modal', function(e) {
            var button = e.relatedTarget;
            var title = button.getAttribute('data-title');
            var id = button.getAttribute('data-id');
            var weight = $('#p-weight-' + id).html();
            var width = $('#p-width-' + id).html();
            var height = $('#p-height-' + id).html();
            var length = $('#p-length-' + id).html();
            var number_of_pieces = $('#p-no-of-pieces-' + id).html();
            var purchase_place = $('#p-place-' + id).html();
            var purchase_price = $('#p-price-' + id).html();
            var note = $('#p-note-' + id).text();
            var content = $('#p-content-' + id).text();
            $('input[name="product_id"]').val(id);
            $(this).find('h5').html(title);
            if (button.getAttribute('data-image') != null) {
                $('.image-input-wrapper').css('background-image', 'url("' + button.getAttribute('data-image') +
                    '")');
            } else {
                $('.image-input-wrapper').css('background-image',
                    'url("{{ asset('assets/img/product-placeholder.webp') }}")');
            }
            $('input[name="weight"]').val(parseFloat(weight));
            $('input[name="height"]').val(parseFloat(height));
            $('input[name="length"]').val(parseFloat(length));
            $('input[name="width"]').val(parseFloat(width));
            $('input[name="number_of_pieces"]').val(number_of_pieces);
            $('input[name="purchase_place"]').val(purchase_place);
            $('input[name="purchase_price"]').val(purchase_price);
            $('textarea[name="note"]').val(note);
            $('textarea[name="content"]').val(content);
            $('#myTable').DataTable().clear().draw();
        });


        // Ajax Form submittion
        $(document).on('submit', '#edit-product-form', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('product.edit') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        $('#p-place-' + response.image_id).html(response.purchase_place);
                        $('#p-no-of-pieces-' + response.image_id).html(response.number_of_pieces);
                        $('#p-price-' + response.image_id).html(response.purchase_price);
                        $('#p-weight-' + response.image_id).html(response.weight);
                        $('#p-width-' + response.image_id).html(response.width);
                        $('#p-length-' + response.image_id).html(response.length);
                        $('#p-height-' + response.image_id).html(response.height);
                        $('#p-note-' + response.image_id).html(response.note);
                        $('#p-content-' + response.image_id).html(response.content);
                        if (response.image_stored) {
                            toastr.success('Image Changed Successfully');
                            $('#btn-' + response.image_id).attr('data-image', response.image_path);
                            var image_to_update = $('#' + response.image_id);
                            image_to_update.prop('src', response.image_path);
                        } else {
                            toastr.success('Poduct Updated Successfully');
                        }
                        $('#exampleModal').modal('hide');
                    }
                },
                error: function(response) {
                    if (response.status == 422) {
                        $.each(response.responseJSON.errors, function(attributes, errors) {
                            $.each(errors, function(key, message) {
                                toastr.error(message);
                            });
                        });
                    } else {
                        toastr.error('Something Went Wrong');
                    }
                }
            });
        });
    </script>
@endsection
