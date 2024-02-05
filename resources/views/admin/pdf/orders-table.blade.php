<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    body {
        font-size: 0.875rem;
        font-weight: normal;
        padding: 0;
        margin: 0;
    }

    .gry-color *,
    .gry-color {
        color: #000;
    }

    table {
        width: 100%;
    }

    table th {
        font-weight: normal;
    }

    table.padding th {
        padding: .25rem .7rem;
    }

    table.padding td {
        padding: .25rem .7rem;
    }

    table.sm-padding td {
        padding: .1rem .7rem;
    }

    .border-bottom td,
    .border-bottom th {
        border-bottom: 1px solid #eceff4;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;

    }
</style>

<body>
    <div style="background: #eceff4;padding: 1rem;">
        <table>
            <tr>
                <td>
                    @if ($logo != null)
                        <img src="{{$logo}}" height="30"
                        style="display:inline-block;">
                    @endif
                </td>
                <td style="font-size: 1.5rem;" class="text-right strong">Orders</td>
            </tr>
        </table>
    </div>

    <div class="table-responsive">
        <table class="padding text-left small border-bottom">
            <thead>
                <tr class="text-dark">
                    {{-- <th scope="col"><input class="form-check-input" type="checkbox" id="check_all"></th> --}}
                    <th scope="col">Order No</th>
                    <th scope="col">Product</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Place Date</th>
                    <th scope="col">Price</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Total</th>
                    {{-- <th scope="col">Date</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        {{-- <td><input class="form-check-input" type="checkbox" name="id[]"
                                value="{{ $order['orderId'] }}"></td> --}}
                        <td>{{ $order['orderId'] }}</td>
                        <td>
                            {{ @$order['orderItems'][0]['product']['title'] }}
                            <br>
                            <span>EAN: {{ @$order['orderItems'][0]['product']['ean'] }}</span>
                        </td>
                        <td>{{ @$order['orderItems'][0]['quantity'] }}</td>
                        <td>{{ \Carbon\Carbon::parse(@$order['orderPlacedDateTime'])->toDateTimeString() }}</td>
                        <td>{{ @$order['orderItems'][0]['unitPrice'] }}</td>
                        <td>{{ @$order['orderItems'][0]['commission'] }}</td>
                        <td>{{ @$order['orderItems'][0]['unitPrice'] + @$order['orderItems'][0]['commission'] }}
                        </td>
                        {{-- <td><a class="btn btn-sm btn-primary" href="">Detail</a></td> --}}
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
