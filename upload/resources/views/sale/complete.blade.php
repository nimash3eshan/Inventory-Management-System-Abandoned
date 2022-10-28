@extends('layouts.sale')
@section('content')
<div class="content-wrapper" ng-app="flexiblepos">
    <!-- Content Header (Page header) -->
    <section class="content-header" ><h1>{{__('Sales/Invoice')}}</h1></section>
    <!-- Main content -->
    @include('sale.print_invoice')
</div>
@endsection