@extends('layouts.sale')
@section('content')
<div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>{{__('Daily Reports')}}<a class="btn btn-small btn-success pull-right" href="{{ URL::to('reports/dailyreport') }}"><i class="fa fa-list"></i>&nbsp; {{__('List')}}</a></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-body">
                        <form action="{{url('reports/dailyreport/create')}}" method="GET" id="pastDateReport">
                            <div class="form-group form-inline hidden-print">
                                <label for="date">{{__('Select Date')}}</label>
                                <input type="text" name="date" id="start_date" class="form-control" onchange="$('#pastDateReport').submit()" required />
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="panel-title text-center" >{{__('DAILY INCOME AND EXPENSE')}}</h3>
                            </div>
                        </div>
                        <div class="row">
                            @include('report.daily_report_table')
                        </div>
                    </div>
                    <div class="box-footer">
                        <p>*** You should close the daily report on the end of the working day to get the accurate result.</p>
                    </div>
                    </div>

            </div>
        </div>
    </section>
</div>
@endsection