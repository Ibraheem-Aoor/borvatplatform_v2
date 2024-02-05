/**
 * This JS Logic handles all the order module
 */
$(document).ready(function () {
    renderDataTable(myParcelAjax);
    $(document).on('change' , 'select[name="shipping_api"]' , function()
    {
        if($(this).val() == 1)
        {
            $('#myTable').DataTable().destroy();
            $('form').attr('action' , printMyParcelabelsUrl);
            renderDataTable(myParcelAjax);

        }else if($(this).val() == 2){
            $('#myTable').DataTable().destroy();
            $('form').attr('action' , printSendyLabelsUrl);
            renderDataTable(sendyAjax);
        }else{
            $('#myTable').DataTable().destroy();
            $('form').attr('action' , printProParcelLabelsUrl);
            renderDataTable(proParcelAjax);
        }
    });
});

function renderDataTable(ajaxUrl)
{
    $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: ajaxUrl,
        columns: [{
            data: 'checkbox',
            name: 'checkbox',
            searchable: false,
            orderable: false,
        },
        {
            data: 'order_api_id',
            name: 'order_api_id',
        },
        {
            data: 'shipment_no',
            name: 'shipment_id',
        },
        {
            data: 'ref',
            name: 'ref',
        },
        {
            data: 'first_name',
            name: 'first_name',
            searchable: false,
            orderable: false,
        },
        {
            data: 'surname',
            name: 'surname',
            searchable: false,
            orderable: false,
        },
        {
            data: 'city',
            name: 'city',
            orderable: false,
            searchable: false,

        },
        {
            data: 'street_name',
            name: 'street_name',
            searchable: false,
            orderable: false,
        },
        {
            data: 'created_at',
            name: 'created_at',
            searchable: false,
            orderable: false,
        },
        ]
    });

}

