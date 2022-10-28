<div id="list_sale_report" class="list-sale-report">
    <table class="table table-striped table-bordered" id="myTable1">
        <thead>
            <tr>
                <th>&nbsp;</th>
                @foreach($units as $key=>$unit)
                    <th>{{$unit}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{__('Total Sales')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ !empty($formated_report[$key]) ? count($formated_report[$key]) : 0}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Sales Amount')}}
                </td>
                @foreach($units as $key=>$unit)
                @php
                $total_amount[$key] = 0;
                $total_payment[$key] = 0;
                $total_dues[$key] = 0;
                $total_discount[$key] = 0;
                $total_tax[$key] = 0;
                if(!empty($formated_report[$key])) {
                    foreach($formated_report[$key] as $value) {
                        $total_amount[$key] = $total_amount[$key] + $value->grand_total;
                        $total_payment[$key] = $total_payment[$key] + $value->payment;
                        $total_dues[$key] = $total_dues[$key] + $value->dues;
                        $total_discount[$key] = $total_discount[$key] + $value->discount;
                        $total_tax[$key] = $total_tax[$key] + $value->tax;
                    }
                }
                @endphp
                <td>{{ currencySymbol().$total_amount[$key]}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Sales Payment')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ currencySymbol().$total_payment[$key]}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Sales Dues')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ currencySymbol().$total_dues[$key]}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Sales Discount')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ currencySymbol().$total_discount[$key]}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Sales Tax')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ currencySymbol().$total_tax[$key]}}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>