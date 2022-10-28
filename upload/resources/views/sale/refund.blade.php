@extends('layouts.sale')
@section('content')
<div class="content-wrapper" ng-app="flexiblepos">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{__('Sales')}}</h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
            @include('partials.flash')
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title"> {{__('Refund Sale')}}</h3></div>
            </div>
            <div class="box box-success">
                <div class="box-header"></div>
                <div class="box-body">
                    
                    <div class="row" ng-controller="SearchItemCtrl">

                        <div class="col-md-3">
                            <label>{{trans('sale.search_item')}} <input ng-model="searchKeyword" class="form-control"></label>

                            <table class="table table-hover">
                            <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">

                            <td>@{{item.item_name}}</td>
                            <td><button class="btn btn-success btn-xs" type="button" ng-click="addSaleTemp(item, newsaletemp)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>

                            </tr>
                            </table>
                        </div>
                        <div class="col-md-9">
                            {{ Form::open(array('url' => $formurl, 'class' => 'form-horizontal')) }}
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="invoice" class="col-sm-3 control-label">{{trans('sale.invoice')}}</label>
                                        <div class="col-sm-9">
                                        <input type="text" class="form-control" id="invoice" value="{{$invoice}}" readonly/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="employee" class="col-sm-3 control-label">{{trans('sale.employee')}}</label>
                                        <div class="col-sm-9">
                                        <input type="text" class="form-control" id="employee" value="{{ $sale->user->name }}" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="customer_id" class="col-sm-4 control-label">{{trans('sale.customer')}}</label>
                                        <div class="col-sm-6 no-margin no-right-padding">
                                            @include('sale.customer_dropdown')
                                        </div>
                                        <div class="col-sm-2 no-margin no-left-padding">
                                            <a class="btn btn-success pull-right" href="{{url('expenses')}}/#addCustomerModal" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Add')}}</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_type" class="col-sm-4 control-label">{{trans('sale.payment_type')}}</label>
                                        <div class="col-sm-8">
                                        {{ Form::select(__('payment_type'), $payment_types, $sale->payment_type, array('class' => 'form-control','placeholder'=>__('Select a payment type'),'required')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('sale.tmp_table')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group hidden">
                                        <label for="total" class="col-sm-5 control-label">{{trans('sale.add_discount_flat')}}</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-addon">{{currencySymbol()}}</div>
                                                <input type="number" class="form-control" name="discount" id="add_payment" ng-model="add_discount" ng-init="add_discount ={{$sale->discount}}" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group hidden">
                                        <label for="total" class="col-sm-5 control-label">{{trans('sale.add_discount_percent')}}</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-addon">{{currencySymbol()}}</div>
                                                <input type="number" class="form-control" name="discount_percent" id="add_payment" ng-model="add_discount_percent" ng-init="add_discount_percent =0" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="total" class="col-sm-5 control-label">{{__('Refund Amount')}}</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-addon">{{currencySymbol()}}</div>
                                                <input type="number" class="form-control" name="payment" id="add_payment" ng-model="add_payment" ng-init="add_payment ={{$sale->payment}}" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="employee" class="col-sm-5 control-label">{{trans('sale.comments')}}</label>
                                        <div class="col-sm-7">
                                        <input type="text" class="form-control" name="comments" id="comments" value="{{$sale->comments}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supplier_id" class="col-sm-4 control-label">{{trans('sale.sub_total')}}</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static subtotal"><b> <input type="text" name="subtotal" value="@{{sum(saletemp)}}" readonly="" class="form-control"></b></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount_due" class="col-sm-4 control-label">{{trans('sale.amount_discount')}}</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static"><b>{{currencySymbol()}}@{{ (sum(saletemp)*add_discount_percent /100) + add_discount }}</b></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount_due" class="col-sm-4 control-label">{{trans('sale.amount_payment')}}</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static"><b>{{currencySymbol()}}@{{ add_payment }}</b></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tax" class="col-sm-4 control-label">{{trans('sale.tax')}} :</label>
                                        <div class="col-sm-8">
                                        <p class="form-control-static">{{currencySymbol()}}@{{ (0*(sum(saletemp))/100)}}</p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="grand_total" class="col-sm-4 control-label">{{trans('sale.grand_dues')}}</label>
                                        <div class="col-sm-8">
                                        <p class="form-control-static">
                                            <b>{{currencySymbol()}}@{{ (sum(saletemp) - add_payment - add_discount - (sum(saletemp)*add_discount_percent /100))+ (0*(sum(saletemp))/100) }}</b>
                                        </p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-success btn-block">{{__('Refund Sale')}}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{ Form::close() }}
                            @include('sale.custom_item')
                        </div>
                    </div>
                </div>
                <div class="box-footer"></div>
            </div>
        </div>
    </div>
</section>
</div>

<div class="modal fade sub-modal" id="addCustomerModal">
    <div class="modal-dialog modal-lg">
        @include('customer.form', ['customer'=>''])
    </div>
</div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('js/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/sale.js')}}"></script>
@endsection