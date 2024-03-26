/**
 * This JS Logic handles all the order module
 */
$(document).ready(function () {
    renderDataTable();
    // change image and preveiw
    $('#uploadButton').on('click', function () {
        $('#changeImg').click();
    })

    $('#changeImg').change(function () {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onloadend = function () {
            $('.image-input-wrapper').css('background-image', 'url("' + reader.result + '")');
        }
        if (file) {
            reader.readAsDataURL(file);
        }
    });
});

//handle modal to create & update
$('#account-create-update-modal').on('shown.bs.modal', function (e) {
    var btn = e.relatedTarget;
    var is_create = btn.getAttribute('data-is-create');
    var action = btn.getAttribute('data-action');
    var method = btn.getAttribute('data-method');
    var imagePath = btn.getAttribute('data-image');

    if (is_create == 1) {
        $(this).find('.modal-header h5').text('Create New Bol Account');
        $(this).find('form').attr('action', action);
        $(this).find('form').attr('method', method);
        $('.image-input-wrapper').css('background-image', 'url("' + imagePath + '")');
        $(this).find('button[type="reset"]').click();
    } else {
        var name = btn.getAttribute('data-name');
        $(this).find('.modal-header h5').text(`Update ${name} BOL Account`);
        $(this).find('form').attr('action', action);
        $(this).find('form').attr('method', method);
        $('.image-input-wrapper').css('background-image', 'url("' + imagePath + '")');
        $('#name').val(name);
        $('#client_id').val(btn.getAttribute('data-client-id'));
        $('#client_key').val(btn.getAttribute('data-client-key'));
        $('#address_street').val(btn.getAttribute('data-address_street'));
        $('#address_city').val(btn.getAttribute('data-address_city'));
        $('#address_country').val(btn.getAttribute('data-address_country'));
        $('#address_zipcode').val(btn.getAttribute('data-address_zipcode'));
    }
});


/**
    * render Datatable
    */
function renderDataTable() {
    $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: api_data_url,
        columns: getTableColumns(),
        order: [[
            3,
            'desc'
        ]],
    });
}

function getTableColumns() {
    return [{
        data: 'checkbox',
        name: 'checkbox',
        orderable: false,
        searchable: false,
    },
    {
        data: 'logo',
        name: 'logo',
        orderable: false,
        searchable: false,
    },
    {
        data: 'name',
        name: 'name',
        orderable: true,
        searchable: true,
    },
    {
        data: 'created_at',
        name: 'created_at',
        orderable: true,
        searchable: true,
    },
    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false,
    },
    ];
}


