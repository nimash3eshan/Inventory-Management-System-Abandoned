@extends('layouts.sale')
@section('content')
<div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>{{__('Sales/Invoices')}}
        @if(auth()->user()->checkSpPermission('sales.create'))
        <a class="btn btn-small btn-success pull-right" href="{{ URL::to('sales/create') }}"><i class="fa fa-plus"></i>&nbsp; {{__('Add Sale')}}</a>
        @endif</h1>
    </section>
    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">
                    <div class="box-header">
                    @include('partials.filters', ['filter_route'=>url('/sales'), 'filter_id'=>'saleFilter'])
                    </div>
                    <div id="list-sale-report">
                        @include('customer.partials.sale_table', ['salereport'=>$sales, 'type'=>'all'])
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</div>
@endsection