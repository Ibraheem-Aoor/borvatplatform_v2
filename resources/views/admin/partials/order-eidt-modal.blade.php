<div>
    {{-- Start Shipping Method Modal --}}
    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="  " method="POST" id="borvat-update-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalHeader"></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="order_id" id="order_id">
                        <div class="row">
                            <label for="">Order Code</label>
                            <input type="text" name="code" id="order_code" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">House No</label>
                                    <input type="text" class="form-control" name="house_no" id="house_no">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Zip Code</label>
                                    <input type="text" class="form-control" name="zip_code" id="zip_code">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Country</label>
                                    <input type="text" class="form-control" name="country" id="country">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">City</label>
                                    <input type="text" class="form-control" name="city" id="city">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Street</label>
                                    <input type="text" class="form-control" name="street" id="street">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Contact Person</label>
                                    <input type="text" class="form-control" name="contact_person"
                                        id="contact_person">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">E-mail</label>
                                    <input type="text" class="form-control" name="email" id="email">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Note: </label><br>
                                    <textarea name="note" cols="55" rows="10" id="note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="save-btn">Save
                            changes</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
{{-- End Shipping Method Modal --}}
