<div class="col-md-12" id="daily_report_table">
    <h4 class="text-center"> {{__('Date')}} : {{!empty($date) ? $date : \Carbon\Carbon::today()->format('Y-m-d')}}</h4>
    @if(count($daily_sales) || count($customer_payments) || count($receiving_payments) || count($expenses) || count($supplier_payments) )
    <form action="{{$action == 'edit' ? route('dailyreport.update', $exist_report->id) : url('reports/dailyreport')}}" method="POST" id="close_daily_report" class="close-daily-report">
        @csrf
        <input type="hidden" name="date" value="{{!empty($date) ? $date : \Carbon\Carbon::today()->format('Y-m-d')}}">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>{{__('Income Source')}}</th>
                    <th>{{__('Expense Source')}}</th>
                    <th>{{__('Credit')}}</th>
                    <th>{{__('Debit')}}</th>
                    <th>{{__('Balance')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{__('Previous Balance')}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @if(empty($exist_report))
                        {{currencySymbol()}}<input type="number" class="hidden-input" name="prev_balance" value="{{$starting_balance}}">
                        @else
                            @if($action == 'edit')
                            {{currencySymbol()}}<input type="number" class="hidden-input" name="prev_balance" value="{{$exist_report->prev_balance}}">
                            @else
                            {{ currencySymbol().$starting_balance = $exist_report->prev_balance}}
                            @endif
                        @endif
                    </td>
                </tr>
            {{--get daily sales --}}
            @foreach($daily_sales as $daily_sale)
                @if($daily_sale->payment > '0.00' )
                <tr>
                    <td>Sales: {{$daily_sale->sale->customer->name}}</td>
                    <td></td>
                    <td>{{currencySymbol().$daily_sale->payment}}</td>
                    <td></td>
                    <td></td>
                </tr>
                @elseif($daily_sale->comments == 'REFUND')
                <tr>
                    <td>Refund Sales: {{$daily_sale->sale->customer->name}}</td>
                    <td></td>
                    <td>{{currencySymbol().$daily_sale->payment}}</td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            @endforeach
        
            {{--get daily payments for individual customer--}}
            @foreach($customer_payments as $customer_payment)
                @if($customer_payment->payment > '0.00')
                <tr>
                    <td>Customer:{{$customer_payment->customer->name}}</td>
                    <td></td>
                    <td>{{currencySymbol().number_format($customer_payment->payment)}}</td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            @endforeach
            {{--get daily receivings--}}
            @foreach($receiving_payments as $receiving_payment)
                @if($receiving_payment->payment > '0.00')
                <tr>
                    <td></td>
                    <td>Purchases: {{$receiving_payment->receiving->supplier->name}}</td>
                    <td></td>
                    <td>{{currencySymbol().number_format($receiving_payment->payment)}}</td>
                    <td></td>
                </tr>
                @endif
            @endforeach
            {{--get supplier payment--}}
            @foreach($supplier_payments as $supplier_payment)
                @if($supplier_payment->payment > '0.00')
                <tr>
                    <td></td>
                    <td>Suppliers: {{$supplier_payment->supplier->name}}</td>
                    <td></td>
                    <td>{{currencySymbol().number_format($supplier_payment->payment)}}</td>
                    <td></td>
                </tr>
                @endif
            @endforeach
            {{--get daily purchases for chicks--}}
            @foreach($expenses as $expense)
                @if($expense->payment > '0.00')
                <tr>
                    <td></td>
                    <td>{{$expense->expense_category->name . " : " .$expense->description}}</td>
                    <td></td>
                    <td>{{currencySymbol().number_format($expense->payment)}}</td>
                    <td></td>
                </tr>
                @endif
            @endforeach
            <tr>
                <td><strong>{{__('Total')}}</strong></td>
                <td></td>
                <td><strong>{{currencySymbol().number_format( $total_credit, 2)}}</strong></td>
                <td><strong>{{currencySymbol().number_format($total_debit, 2)}}</strong></td>
                <td><strong>{{currencySymbol().number_format($starting_balance + $total_credit - $total_debit, 2)}}
                </strong></td>
            </tr>
            </tbody>
        </table>
        </form>
        @if(empty($dailyreport) || is_null($exist_report))
            <button class="hidden-print btn btn-success pull-right" onclick="submitWithConfirm('#close_daily_report','Are you Sure You want to close the report!')">{{__('Close Report')}} </button>
        @elseif(!empty($exist_report) && $action == 'edit')
            <button class="hidden-print btn btn-warning pull-right" onclick="submitWithConfirm('#close_daily_report', 'Are you Sure You want to update the report!')">{{__('Update Report')}} </button>
        @endif
    @else
        <h3 class="alert alert-warning text-center">{{__('No reports Available')}}</h3>
    @endif
</div>