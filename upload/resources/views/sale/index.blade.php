@extends('layouts.sale')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>@if($action == 'edit')
            {{__('Edit Sale')}}
            @elseif($action == 'refund')
            {{__('Refund Sale')}}
            @else
            {{__('Add Sale')}}
            @endif</h1>
    </section>
    <!-- Main content -->
    @include('sale.sale_content')
</div>

<div class="modal fade sub-modal" id="addCustomerModal">
    <div class="modal-dialog modal-lg">
        @include('customer.form', ['customer'=>'', 'page'=>'sale'])
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript" src="{{asset('js/angular.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/sale.js')}}"></script>
@endsection