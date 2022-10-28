<section class="content" id="saleContent">
<div class="row">
    <div class="col-xs-12">
        <div class="box box-success" ng-app="flexiblepos">
            <div class="box-header"></div>
            <div class="box-body">
                <div class="row" ng-controller="SearchItemCtrl">
                    <div class="col-sm-3 pr-0">
                        <label>{{trans('sale.search_item')}} <input ng-model="searchKeyword" class="form-control"></label>
                        <table class="table table-hover">
                        <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:15">
                           
                            <td width="20%"><img src="@{{item.avatar}}" alt="" width="60" /></td>
                            <td width="70%">@{{item.item_name}}</td> 
                            <td width="10%"><button class="btn btn-success btn-xs" type="button" ng-click="addSaleTemp(item, newsaletemp)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>
                        </tr>
                        </table>
                    </div>
                    <div class="col-sm-9">
                        {{ Form::open(array('url' => $formurl, 'class' => 'form-horizontal', 'id'=>'saleForm')) }}
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="invoice" class="col-sm-4 col-md-3 control-label text-right">{{trans('sale.invoice')}}</label>
                                    <div class="col-sm-8 col-md-9">
                                    <input type="text" name="invoice_no" class="form-control" id="invoice" value="{{$action == 'add' ? $invoice : $sale->invoice_no}}" readonly/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="account_id" class="col-sm-4 col-md-3 text-right control-label">{{__('Accounts')}}</label>
                                    <div class="col-sm-8 col-md-9">
                                        {{Form::select('account_id', $accounts, null, ['class'=>'form-control', 'placeholder'=>'Select Account', 'required'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <label for="customer_id" class="col-sm-4 col-xs-12 pr-0 control-label">{{trans('sale.customer')}}</label>
                                    <div class="col-sm-6 col-xs-8 no-margin no-right-padding">
                                        @include('sale.customer_dropdown')
                                    </div>
                                    <div class="col-sm-2 col-xs-4 no-margin no-left-padding">
                                        <a class="btn btn-success pull-right" href="#addCustomerModal" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Add')}}</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="payment_type" class="col-sm-4 pr-0 control-label">{{trans('sale.payment_type')}}</label>
                                    <div class="col-sm-8">
                                    {{ Form::select(__('payment_type'), $payment_types, (!empty($sale) ? $sale->payment_type : null), array('class' => 'form-control','placeholder'=>__('Select a payment type'),'required')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('sale.tmp_table')
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="total" class="col-sm-6 col-md-5 pr-0 control-label">{{trans('sale.add_discount_flat')}}</label>
                                    <div class="col-sm-6 col-md-7">
                                        <div class="input-group">
                                            <div class="input-group-addon">{{currencySymbol()}}</div><input type="number" class="form-control" name="discount" id="add_payment" ng-model="add_discount" ng-init="add_discount ={{!empty($sale->discount) ? $sale->discount : 0}}" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="total" class="col-sm-6 col-md-5 pr-0 control-label">{{trans('sale.add_discount_percent')}}</label>
                                    <div class="col-sm-6 col-md-7">
                                        <div class="input-group">
                                            <div class="input-group-addon">{{currencySymbol()}}</div><input type="number" class="form-control" name="discount_percent" id="add_payment" ng-model="add_discount_percent" ng-init="add_discount_percent=0" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="total" class="col-sm-6 col-md-5 pr-0 control-label">{{trans('sale.add_tax_percent')}}</label>
                                    <div class="col-sm-6 col-md-7">
                                        <div class="input-group">
                                            <div class="input-group-addon">{{currencySymbol()}}</div><input type="number" class="form-control" name="tax_percent" id="tax_percent" ng-model="add_tax_percent" ng-init="add_tax_percent=0" step="0.01" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    @if($action == 'refund')
                                    <label for="total" class="col-sm-6 col-md-5 pr-0 control-label">{{__('Refund Amount')}}</label>
                                    @else
                                    <label for="total" class="col-sm-6 col-md-5 pr-0 control-label">{{trans('sale.add_payment')}}</label>
                                    @endif
                                    <div class="col-sm-6 col-md-7">
                                        <div class="input-group">
                                            <div class="input-group-addon">{{currencySymbol()}}</div><input type="number" class="form-control" name="payment" id="add_payment" ng-model="add_payment" ng-init="add_payment={{!empty($sale->payment) ? $sale->payment : 0}}" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="employee" class="col-sm-6 col-md-5 pr-0 control-label">{{trans('sale.comments')}}</label>
                                    <div class="col-sm-6 col-md-7">
                                    <input type="text" class="form-control" value="{{!empty($sale->comments) ? $sale->comments : ''}}" name="comments" id="comments" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="supplier_id" class="col-xs-6 col-md-4 control-label">{{trans('sale.sub_total')}}</label>
                                    <div class="col-xs-6 col-md-8">
                                        <p class="form-control-static subtotal"><b> <input type="text" name="subtotal" value="@{{sum(saletemp)}}" readonly="" class="form-control"></b></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="amount_due" class="col-xs-6 col-md-4 control-label">{{trans('sale.amount_discount')}}</label>
                                    <div class="col-xs-6 col-md-8">
                                        <p class="form-control-static"><b>{{currencySymbol()}}@{{ (sum(saletemp)*add_discount_percent /100) + add_discount }}</b></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="amount_due" class="col-xs-6 col-md-4 control-label">{{trans('sale.amount_payment')}}</label>
                                    <div class="col-xs-6 col-md-8">
                                        <p class="form-control-static"><b>{{currencySymbol()}}@{{ add_payment }}</b></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tax" class="col-xs-6 col-md-4 control-label">{{trans('sale.tax')}} :</label>
                                    <div class="col-xs-6 col-md-8">
                                    <p class="form-control-static">{{currencySymbol()}}@{{ (sum(saletemp)*add_tax_percent /100)}}</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="grand_total" class="col-xs-6 col-md-4 control-label">{{trans('sale.grand_dues')}}</label>
                                    <div class="col-xs-6 col-md-8">
                                        <p class="form-control-static"><b>{{currencySymbol()}}@{{ (sum(saletemp) - add_payment - add_discount - (sum(saletemp)*add_discount_percent /100) - (sum(saletemp)*add_tax_percent /100)) + (0*(sum(saletemp))/100) }}</b></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    @if($action == 'edit')
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-success btn-block">{{__('Update Sale')}}</button>
                                        </div>
                                    @elseif($action == 'refund')
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-success btn-block">{{__('Refund Sale')}}</button>
                                        </div>
                                    @else
                                        <div class="col-sm-7 col-md-6">
                                            <button type="submit" class="btn btn-success btn-block">{{trans('sale.submit')}}</button>
                                        </div>
                                        <div class="col-sm-5 col-md-6">
                                            <button type="button" id="holdSale" class="btn btn-warning btn-block">{{__('Hold')}}</button>
                                        </div>
                                    @endif
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
