<div class="row">
    @if(!empty($stock_limit_items))
    <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h4>{{__('Stock Alert')}}</h4>
            </div>
            <div class="box-body">
                @include('dashboard.partials.stock_table', ['stock_limit_items'=>$stock_limit_items])
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-4">
        <div class="box box-success single-latest">
            <div class="box-header with-border">
                <h4>{{__('Latest Incomes')}}</h4>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Total')}}</th>
                            <th>{{__('Payment')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latest_incomes->take(5) as $item)
                        <tr>
                            <td>{{date('d M Y', strtotime($item->created_at))}}</td>
                            <td>{{currencySymbol().$item->grand_total}}</td>
                            <td>{{currencySymbol().$item->payment}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-success single-latest">
            <div class="box-header with-border">
                <h4>{{__('Latest Expenses')}}</h4>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Total')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aexpenses->take(5) as $item)
                        <tr>
                            <td>{{date('d M Y', strtotime($item->created_at))}}</td>
                            <td>{{$item->expense_category->name}}</td>
                            <td>{{currencySymbol().$item->total}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-success single-latest">
            <div class="box-header with-border">
                <h4>{{__('Account Balance')}}</h4>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tbody>
                        @foreach ($accounts->take(6) as $item)
                        <tr>
                            <td>{{$item->company}}</td>
                            <td>{{currencySymbol().$item->balance}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>