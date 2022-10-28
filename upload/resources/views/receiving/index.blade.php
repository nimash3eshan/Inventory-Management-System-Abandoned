@extends('layouts.sale')

@section('content')
<div class="content-wrapper" ng-app="tutapos">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3"><h1>{{__('Recevings/Purchases')}}</h1></section>
    <!-- Main content -->
    @include('receiving.receiving_content')
</div>
<div class="modal fade sub-modal" id="addSupplierModal">
    <div class="modal-dialog modal-lg">
        @include('supplier.form', ['supplier'=>'', 'page'=>'receiving'])
    </div>
</div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('js/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/recevings.js')}}"></script>
@endsection