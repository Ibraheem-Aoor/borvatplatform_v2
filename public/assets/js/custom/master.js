$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf"]').attr('content'),
        },
    })

    /* ##############  START DELETE FORM  ##########*/
    $('#delete-modal').on('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        var deleteUrl = btn.getAttribute('data-delete-url');
        var message = btn.getAttribute('data-message');
        var name = btn.getAttribute('data-name');
        var modalForm = $(this).find('form[name="confirm-delete-form"]');
        modalForm.attr('action', deleteUrl);
        modalForm.attr('method', 'DELETE');
        $(this).find('.modal-body p').text(message + "\t" + name);
    });
    //Handle delete confirmation form
    $(document).on('submit', 'form[name="confirm-delete-form"]', function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: {},
            success: function (response) {
                if (response.is_deleted) {
                    toastr.success(response.message);
                    $('#row-' + response.row).parent().parent().remove();
                    $('#delete-modal').modal('hide');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (response) {
                toastr.error(response.message);
            }
        });
    });
    /* ##############  END DELETE FORM  ##########*/




    $(document).on('submit', 'form.custom-form', function (e) {

        e.preventDefault();
        $('#spinner').addClass('show');
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            processData: false,
            contentType: false,
            data: formData,
            enctype: "multipart/form-data",
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                if (response.reset_form) {
                    $(this).trigger('reset');
                }
                var table = $('#myTable').DataTable();

                // Store current page and selected rows before reloading
                var currentPage = table.page();
                var selectedRows = table.rows({ selected: true }).data().toArray();

                if (response.table_reload) {
                    table.ajax.reload(function () {
                        // Restore previous page and reselect records
                        table.page(currentPage).draw(false);

                        selectedRows.forEach(function (row) {
                            var index = table.rows().eq(0).filter(function (rowIdx) {
                                return table.cell(rowIdx, 0).data() === row[0];
                            });

                            if (index.length > 0) {
                                table.row(index).select();
                            }
                        });
                    });
                }
                if (response.modal_to_hide) {
                    $(response.modal_to_hide).modal('hide');
                }
                $('#spinner').removeClass('show');
            }, error: function (response) {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, errorsArray) {
                        $.each(errorsArray, function (item, error) {
                            toastr.error(error);
                        });
                    });
                } else if (response.responseJSON && response.responseJSON.message) {
                    toastr.error(response.responseJSON.message);
                } else {
                    toastr.error(response.message);
                }
                $('#spinner').removeClass('show');
            }
        });
    });



    // English Inputs
    // Get all input elements with the specified class
    const inputs = document.querySelectorAll('.en-only');

    // Iterate over each input and attach event listeners
    inputs.forEach((input) => {
        input.addEventListener('input', (event) => {
            const inputValue = event.target.value;
            const englishCharsRegex = /^[a-zA-Z0-9 -]*$/;

            if (!englishCharsRegex.test(inputValue)) {
                const englishCharsOnly = inputValue.replace(/[^a-zA-Z0-9 -]/g, '');
                event.target.value = englishCharsOnly;
            }
        });
    });


    // Arabic Inputs
    // Get all input elements with the specified class
    const arabicInputs = document.querySelectorAll('.ar-only');

    // Iterate over each input and attach event listeners
    arabicInputs.forEach((input) => {
        input.addEventListener('input', (event) => {
            const arabicInputValue = event.target.value;
            const arabicCharsRegex = /^[\u0600-\u06FF0-9 -]*$/;

            if (!arabicCharsRegex.test(arabicInputValue)) {
                const arabicCharsOnly = arabicInputValue.replace(/[^\u0600-\u06FF0-9 -]/g, '');
                event.target.value = arabicCharsOnly;
            }
        });
    });

});
