<div id="list_receiving_report" class="list-sale-report">
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
                    {{__('Total Purchases')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ !empty($formated_report[$key]) ? count($formated_report[$key]) : 0}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Purchase Amount')}}
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
                        $total_amount[$key] = $total_amount[$key] + $value->total;
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
                    {{__('Purchase Payment')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ currencySymbol().$total_payment[$key]}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Purchase Dues')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ currencySymbol().$total_dues[$key]}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Purchase Discount')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ currencySymbol().$total_discount[$key]}}</td>
                @endforeach
            </tr>
            <tr>
                <td>
                    {{__('Purchase Tax')}}
                </td>
                @foreach($units as $key=>$unit)
                <td>{{ currencySymbol().$total_tax[$key]}}</td>
                @endforeach
            </tr>
            @if(!empty($formated_expense))
                @foreach($expense_cat as $key=>$value)
                <tr>
                    <td>
                        {{__($value->name)}}
                    </td>
                    @foreach($units as $key=>$unit)
                    @php
                        $total_expense_payment = 0;
                        if(!empty($formated_expense[$key])) {
                            foreach($formated_expense[$key] as $avalue) {
                                if($avalue->expense_category_id == $value->id) {
                                    $total_expense_payment = $total_expense_payment + $avalue->payment;
                                }
                            }
                        }
                    @endphp
                
                    <td>{{ currencySymbol().$total_expense_payment}}</td>
                    @endforeach
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>