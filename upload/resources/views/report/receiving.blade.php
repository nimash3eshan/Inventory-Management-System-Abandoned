@extends('layouts.sale')
@section('content')
<div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{__('Expense Report (incl. Receivings/Purchases, Expenses)')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-md-12">
            @include('partials.flash')
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title">{{__('Expense Summary')}}</h3></div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="well well-sm">{{trans('report-sale.grand_total')}}: {{DB::table('receivings')->where('status', '!=', App\Receiving::HOLD)->sum('total') + DB::table('receivings')->sum('total')}}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="well well-sm">{{trans('report-sale.payment')}}: {{DB::table('receivings')->where('status', '!=', App\Receiving::HOLD)->sum('payment')+ DB::table('receivings')->sum('payment')}}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="well well-sm">{{trans('report-sale.dues')}}: {{DB::table('receivings')->where('status', '!=', App\Receiving::HOLD)->sum('dues') + DB::table('receivings')->sum('dues')}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-success">
                <div class="box-header">
                    <form action="" method="GET" id="saleReportFilterForm">
                        <select name="month" id="month" onchange="submitForm('#saleReportFilterForm')" >
                            <option value="">Month</option>
                            @foreach($months as $key=>$month)
                            <option value="{{$key}}"
                            {{(!empty($input['month']) && $input['month'] == $key) ? 'selected' : ''}}
                            >{{$month}}</option>
                            @endforeach
                        </select>
                        <select name="year" id="year" onchange="submitForm('#saleReportFilterForm')">
                            <option value="">Year</option>
                            @php 
                            $year = date('Y'); @endphp
                            @for($i= $year; $i>=($year - 5); $i--)
                            <option value="{{$i}}" 
                            {{(!empty($input['year']) && $input['year'] == $i) ? 'selected' : ''}}
                            >{{$i}}</option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="box-body">
                   @include('report.list_receiving_report')
                </div>
            </div>
        </div>
    </div>
    </section>
</div>
@endsection
