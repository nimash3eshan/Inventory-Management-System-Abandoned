<section class="content" id="receivingContent">
<div class="row">
<div class="col-xs-12">
    <div class="box box-success">
        <div class="box-header"></div>    
        <div class="box-body">
            @include('partials.flash')
            <div class="row" ng-controller="SearchItemCtrl">
                <div class="col-sm-3 pr-0">
                    <label>{{trans('receiving.search_item')}} <input ng-model="searchKeyword" class="form-control"></label>
                    <table class="table table-hover">
                        <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">
                            <td width="20%"><img src=@{{item.avatar}} alt="" width="60"></td>
                            <td width="70%">@{{item.item_name}}</td><td width="10%"><button class="btn btn-success btn-xs" type="button" ng-click="addReceivingTemp(item,newreceivingtemp)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-9">
                    <div class="row">
                        {{ Form::open(array('url' => 'receivings', 'class' => 'form-horizontal')) }}
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="invoice" class="col-sm-4 control-label">{{trans('receiving.invoice')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" name="invoice_no" class="form-control" id="invoice" value="{{$action == 'add' ? $invoice : $receiving->invoice_no}}" readonly/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="account_id" class="col-md-4 text-right control-label">{{__('Accounts')}}</label>
                                <div class="col-md-8">
                                    {{Form::select('account_id', $accounts, null, ['class'=>'form-control'])}}
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="supplier_id" class="col-sm-4 col-xs-12 pr-0 control-label">{{trans('receiving.supplier')}}</label>
                                <div class="col-sm-6 col-xs-9 no-margin no-right-padding">
                                    @include('receiving.supplier_dropdown')
                                </div>
                                <div class="col-sm-2 col-xs-3 no-margin no-left-padding">
                                    <a class="btn btn-success pull-right" href="{{url('expenses')}}/#addSupplierModal" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Add')}}</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment_type" class="col-sm-4 pr-0 control-label">{{trans('receiving.payment_type')}}</label>
                                <div class="col-sm-8">
                                {{ Form::select('payment_type', $payment_types, null, array('class' => 'form-control','placeholder'=>'Select a Payment type', 'required')) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <tr><th class="hidden-xs">{{trans('receiving.item_id')}}</th><th>{{trans('receiving.item_name')}}</th><th>{{trans('receiving.cost')}}</th><th>{{trans('receiving.quantity')}}</th><th>{{trans('receiving.total')}}</th><th>&nbsp;</th></tr>
                        <tr ng-repeat="newreceivingtemp in receivingtemp">
                        <td class="hidden-xs">@{{newreceivingtemp.item_id}}</td><td>@{{newreceivingtemp.item_name}}</td><td>{{currencySymbol()}}@{{newreceivingtemp.item.cost_price }}</td><td><input type="text" style="text-align:center" autocomplete="off" name="quantity" ng-change="updateReceivingTemp(newreceivingtemp)" ng-model="newreceivingtemp.quantity" size="2"></td><td>{{currencySymbol()}}@{{newreceivingtemp.item.cost_price * newreceivingtemp.quantity }}</td><td><button class="btn btn-danger btn-xs" type="button" ng-click="removeReceivingTemp(newreceivingtemp.id)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center"><a href="#" data-toggle="modal" data-target=".bs-example-modal-lg">{{__('Add Quick Product')}}</a></td>
                        </tr>
                    </table>
                    <div class="row">
                        <div class="col-md-7 col-sm-6">
                            <div class="form-group row">
                                <label for="total" class="col-sm-4 control-label">{{trans('receiving.amount_tendered')}}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">{{currencySymbol()}}</div>
                                        <input type="text" class="form-control" name="total" id="amount_tendered" ng-model="amount_tendered" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total" class="col-sm-4 control-label">{{trans('receiving.amount_payment')}}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">{{currencySymbol()}}</div>
                                        <input type="text" class="form-control" name="payment" id="payment" ng-model='payment' required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="employee" class="col-sm-4 control-label">{{trans('receiving.comments')}}</label>
                                <div class="col-sm-8">
                                <input type="text" class="form-control" name="comments" id="comments" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-6">
                            <div class="form-group row">
                                <label for="supplier_id" class="col-xs-6 control-label">{{trans('receiving.grand_total')}}</label>
                                <div class="col-xs-6">
                                    <p class="form-control-static"><b>{{currencySymbol()}}@{{sum(receivingtemp) }}</b></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="supplier_id" class="col-xs-6 control-label">{{trans('receiving.amount_tendered')}}</label>
                                <div class="col-xs-6">
                                    <p class="form-control-static"><b>{{currencySymbol()}}@{{ amount_tendered }}</b></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="supplier_id" class="col-xs-6 control-label">{{trans('receiving.amount_payment')}}</label>
                                <div class="col-xs-6">
                                    <p class="form-control-static"><b>{{currencySymbol()}}@{{ payment }}</b></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="supplier_id" class="col-xs-6 control-label">{{trans('receiving.amount_dues')}}</label>
                                <div class="col-xs-6">
                                    <p class="form-control-static"><b>{{currencySymbol()}}@{{amount_tendered - payment }}</b></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success btn-block">{{trans('receiving.submit')}}</button>
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