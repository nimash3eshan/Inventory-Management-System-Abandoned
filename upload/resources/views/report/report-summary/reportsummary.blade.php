@extends('layouts.admin_dynamic')

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>{{__('Daily Reports')}}<a class="btn btn-small btn-success pull-right" href="{{ URL::to('reports/dailyreport/create') }}"><i class="fa fa-plus"></i>&nbsp; {{__('Create Daily Report')}}</a></h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
              <div class="box-header">
                @include('partials.filters', ['filter_route'=>url('reports/dailyreport'), 'filter_id'=>'dailyreportFilter'])
              </div>
              <div class="box-body">
                @include('report.report-summary.summary_table')
              </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
@endsection