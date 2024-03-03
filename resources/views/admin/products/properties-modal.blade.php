  {{-- Start product edit  Modal --}}
  <div class="modal fade" id="product-properities-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
          <form action="" class="custom-form">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      {{-- product properites --}}
                      <label for="name">Property</label>
                      <div class="container" id="props-container">
                          <div class="row">
                              <div class="col-8">
                                  <div class="form-group p-3 d-flex">
                                      <input type="text" name="properties[0]"  class="form-control product-props-input">
                                      &nbsp;
                                      <input type="checkbox" style="width:30px !important;" name="active_properities[0]" class="product-props-status">
                                  </div>
                              </div>
                              <div class="col-2">
                                  <button type="button" class="add-feature btn-sm mt-3 btn-primary"
                                      onclick="addNewFeature($(this));"><i class="fa fa-plus"></i></button>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
              </div>
          </form>

      </div>
  </div>
  {{-- End Product Edit Modal --}}
