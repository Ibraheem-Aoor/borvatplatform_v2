<div>
    {{-- Start Shipping Method Modal --}}
    <div class="modal fade" id="editShipmentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('shippment.store-note') }}" method="POST" name="note-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editShipmentHeader"></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Note</label>
                                    <textarea name="note" cols="30" rows="10" required class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">SAVE</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>
{{-- End Shipping Method Modal --}}
