  {{-- Start product edit  Modal --}}
  <div class="modal fade" id="product-update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form action="{{ route('product.edit') }}" class="custom-form" method="POST" id="edit-product-form">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
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

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
              </form>

          </div>
      </div>
  </div>
  {{-- End Product Edit Modal --}}
