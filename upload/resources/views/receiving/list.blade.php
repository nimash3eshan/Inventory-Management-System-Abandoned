@extends('layouts.sale')

@section('content')
<div class="content-wrapper" ng-app="tutapos">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
        <h1>{{__('Recevings/Purchases')}}
            @if(auth()->user()->checkSpPermission('sales.create'))
                <a class="btn btn-small btn-success pull-right" href="{{ URL::to('receivings/create') }}"><i class="fa fa-plus"></i>&nbsp; {{__('Add Recevings')}}</a>
            @endif
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        @include('partials.filters', ['filter_route'=>url('/receivings'), 'filter_id'=>'receivingFilter'])
                    </div>
                    <div class="box-body">
                        <div id="list-receiving-report">
                        @include('supplier.partials.receiving_table', ['receivingreport'=>$receivings, 'type'=>'all'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
