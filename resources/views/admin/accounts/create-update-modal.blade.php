<div>
    <div class="modal fade" id="account-create-update-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="" id="borvat-update-form" class="custom-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalHeader"></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="avatar-picture">
                            <div class="image-input image-input-outline" id="imgUserProfile">
                                <div class="image-input-wrapper"
                                    style="background-image: url('{{ asset('assets/img/product-placeholder.webp') }}');">
                                </div>

                                <label class="btn">
                                    <i>
                                        <img src="{{ asset('assets/img/edit.svg') }}" alt="" class="img-fluid">
                                    </i>
                                    <input type="file" name="logo" id="changeImg"
                                        accept=".png, .jpg, .jpeg">
                                    <input type="button" value="Upload" id="uploadButton">
                                </label>

                            </div>
                        </div>
                        <div class="row">
                            <label for="">Account Name:</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="row mt-3">
                            <label for="">Client ID:</label>
                            <input type="text" name="client_id" id="client_id" class="form-control">
                        </div>
                        <div class="row mt-3">
                            <label for="">Client Key:</label>
                            <input type="text" name="client_key" id="client_key" class="form-control">
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <label for="">Address Street:</label>
                                <input type="text" name="address[street]" id="address_street" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label for="">Address City:</label>
                                <input type="text" name="address[city]" id="address_city" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label for="">Address Country:</label>
                                <input type="text" name="address[country]" id="address_country" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label for="">Address Zipcode:</label>
                                <input type="text" name="address[zipcode]" id="address_zipcode" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="save-btn">Save
                            changes</button>
                        <button type="reset" hidden class="btn btn-primary" id="save-btn">Save
                            changes</button>
                    </div>

                </form>
            </div>


        </div>
    </div>
</div>
