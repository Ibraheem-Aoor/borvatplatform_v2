/**
 * This JS Logic handles all the order module
 */
$(document).ready(function () {
    renderDataTable();
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
            6,
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
        data: 'image',
        name: 'image',
        orderable: false,
        searchable: false,
    },
    {
        data: 'api_id',
        name: 'api_id',
        orderable: true,
        searchable: true,
    },
    {
        data: 'account',
        name: 'account',
        orderable: true,
        searchable: true,
    },
    {
        data: 'title',
        name: 'title',
        orderable: false,
        searchable: true,
    },
    {
        data: 'quantity',
        name: 'quantity',
        orderable: true,
        searchable: false,
    },
    {
        data: 'place_date',
        name: 'place_date',
        orderable: true,
        searchable: true,

    },
    {
        data: 'unit_price',
        name: 'unit_price',
        orderable: false,
        searchable: true,
    },
    {
        data: 'country_code',
        name: 'country_code',
        searchable: true,
        orderable: true,
    },
    {
        data: 'total',
        name: 'total',
        searchable: false,
        orderable: false,
    },
    ];
}


