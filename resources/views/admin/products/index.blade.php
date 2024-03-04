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
                            {{-- <th scope="col">Width</th>
                            <th scope="col">Height</th>
                            <th scope="col">Length</th>
                            <th scope="col">Note</th>
                            <th scope="col">Content</th> --}}
                            <th scope="col">Action</th>
                            {{-- <th scope="col">Date</th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->

    @include('admin.products.update-modal')
    @include('admin.products.properties-modal')
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

        $('#product-update-modal').on('shown.bs.modal', function(e) {
            $('#edit-product-form')[0].reset();
            var button = e.relatedTarget;
            var title = button.getAttribute('data-title');
            var id = button.getAttribute('data-id');
            var weight = button.getAttribute('data-weight');
            var width = button.getAttribute('data-width');
            var height = button.getAttribute('data-height');
            var length = button.getAttribute('data-length');
            var number_of_pieces = button.getAttribute('data-number_of_pieces');
            var purchase_place =  button.getAttribute('data-purchase_place');
            var purchase_price = button.getAttribute('data-purchase_price');
            var note = button.getAttribute('data-note');
            var content = button.getAttribute('data-content');
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
            $('textarea[name="note"]').text(note);
            $('textarea[name="content"]').text(content);
        });

    </script>

    {{-- product properties --}}
    <script>
        var counter = 1;

        function addNewFeature(button) {
            var html = `<div class="row">
                              <div class="col-8">
                                  <div class="form-group p-3 d-flex">
                                      <input type="text" name="properties[${counter}]"  class="form-control product-props-input"> &nbsp;
                                      <input type="checkbox"  style="width:30px !important;" name="active_properities[${counter}]" class="product-props-status">

                                  </div>

                              </div>
                              <div class="col-2 d-flex">
                                  <button type="button" class="add-feature btn-sm mt-3 btn-primary"
                                      onclick="addNewFeature($(this));"><i class="fa fa-plus"></i></button> &nbsp;
                                      <button type="button" class="remove_feature btn-sm mt-3 btn-danger"
                onclick="deleteFeature($(this));"><i class="fa fa-trash"></i></button>
                              </div>
                          </div>`;
            ++counter;
            button.parent().parent().after(html);
        };

        function deleteFeature(btn) {
            btn.parent().parent().remove();
        };
    </script>

    <script>
        $('#product-properities-modal').on('shown.bs.modal', function(e) {
            var btn = e.relatedTarget;
            $(this).find('form').attr('action', btn.getAttribute('data-action'));
            $(this).find('form').attr('method', btn.getAttribute('data-method'));
            $(this).find('.modal-title').html(btn.getAttribute('data-title'));
            var jsonData = btn.getAttribute('data-props');
            var props = JSON.parse(jsonData);
            handleProductPropsDisplay(props);
        });
        // Handle Dispaying of The Product Properties.
        function handleProductPropsDisplay(props) {
            if (props.length > 0) {
                $('#props-container').html('');
                var props_html = ``;
                // Populate properties
                $.each(props, function(index, property) {
                    var is_checked = property.is_active ? 'checked' : '';
                    var propertyInput = `<div class="row"><div class="col-8"><div class="form-group p-3 d-flex">
                        <input type="text" name="properties[${counter}]" value="${property.name}" class="form-control product-props-input">
                        &nbsp;
                        <input type="checkbox" style="width:30px !important;" name="active_properities[${counter++}]" class="product-props-status"  ${is_checked}
                        >
                        </div></div> <div class="col-2 d-flex">
                                  <button type="button" class="add-feature btn-sm mt-3 btn-primary"
                                      onclick="addNewFeature($(this));"><i class="fa fa-plus"></i></button> &nbsp;
                                      <button type="button" class="remove_feature btn-sm mt-3 btn-danger"
                onclick="deleteFeature($(this));"><i class="fa fa-trash"></i></button>
                              </div></div>`;
                    props_html += propertyInput;
                });
                $('#props-container').html(props_html);
            } else {
                var default_html = `<div class="row"> <div class="col-8">
                                  <div class="form-group p-3 d-flex">
                                      <input type="text" name="properties[0]"  class="form-control product-props-input">
                                      &nbsp;
                                      <input type="checkbox" style="width:30px !important;" name="active_properities[0]" class="product-props-status">
                                  </div>
                              </div>
                              <div class="col-2">
                                  <button type="button" class="add-feature btn-sm mt-3 btn-primary"
                                      onclick="addNewFeature($(this));"><i class="fa fa-plus"></i></button>
                              </div></div>`;
                counter = 1;
                $('#props-container').html(default_html);
            }
        }
    </script>
@endsection
