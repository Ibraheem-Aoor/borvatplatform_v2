<ul>
    <li class="red">
        @if($shippment->order)Bol.com nr: @else borvat.com : @endif{{ $shippment->order->api_id ?? $shippment->code }}
    </li>
    <li>
        Afzender:
    </li>
    <li>
        {{ $shipment_sender_details['company'] }}
    </li>
    <li>
        {{ $shipment_sender_details['street_and_house'] }}
    </li>
    <li>
        {{ $shipment_sender_details['city_and_zip'] }}
    </li>
</ul>
