<section class="content panel print-content" id="saleContent">
    <div class="invoice-top-header">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-3 pr-0 bottom_border text-left">
                <div class="sale-logo">
                    @if(!empty(setting('logo_path')))
                    <img src="{{asset(\Storage::url(setting('logo_path')))}}" alt="logo" >
                    @else
                    <img src="{{asset('images/fpos.png')}}" alt="logo">
                    @endif 
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-7 p-0 bottom_border text-center ">
                @if(!empty(setting('logo_path')))
                    <h1 class="company-name">{{setting('company_name')}}</h1>
                @else
                <strong>{{__('FLEXIBLEPOS')}}</strong><br>
                @endif 
                
                @if(!empty(setting('company_address')))
                    {{setting('company_address')}}<br>
                @else
                {{__('A reliable Company for your Business Software')}}<br>
                {{__('PHONE')}} : 01333243243266<br>
                {{__('Chandgaon R/A,')}}<br>
                {{__('Chandgaon, Chittagong, Bangladesh.')}}
                @endif 
            </div>
            <div class="col-md-3 col-sm-3 col-xs-2"></div>
        </div>
    </div>
    <div class="row header-border margin-bottom-15"></div>
    <div class="row bottom_border sale-heading-info">
        <div class="col-md-7 col-sm-7 col-xs-6 text-left">
            {{trans('sale.customer')}}: {{ $sales->customer->name}}<br />
            @if(!empty($sales->customer->address))
            {{trans('sale.address')}}: {{ $sales->customer->address}}<br />
            @endif
            {{trans('sale.sale_id')}}: SAL-INV-{{$sales->id}}<br />
        </div>
        <div class="col-md-5 col-sm-5 col-xs-6 text-right">
            Date : {{ Carbon\Carbon::now() }}<br>
            @if(!empty($sales->customer->phone_number))
            {{trans('sale.mobile')}} : {{$sales->customer->phone_number}}<br>
            @endif
            {{trans('sale.employee')}}: {{$sales->user->name}}<br />
        </div>
    </div>
    <div class="row"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive font-size-15">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{trans('sale.item')}}</th>
                            <th>{{trans('sale.price')}}</th>
                            <th>{{trans('sale.qty')}}</th>
                            <th align="right">{{trans('sale.total')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($saleItems as $value)
                        <tr>
                            <td>{{$value->item->item_name}}</td>
                            <td>{{$value->selling_price}}</td>
                            <td>{{$value->quantity}}</td>
                            <td align="right">{{$value->total_selling}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row bottom_border print_footer font-size-15">
        <div class="col-md-7 col-sm-7 col-xs-6">
            {{trans('sale.payment_type')}}: {{$sales->payment_type}}
        </div>
        <div class="col-md-2 col-sm-1"></div>
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="row invoice-footer">
                <div class="col-xs-5">{{__('Subtotal')}}: </div><div class="col-xs-7"> <span class="text-left">{{currencySymbol()}}</span><span class="pull-right"> {{ $sales->discount + $sales->grand_total - $sales->tax }}</span></div>
                <div class="col-xs-5">{{__('Discount')}}: </div>
                <div class="col-xs-7"> 
                    <span class="text-left"> {{currencySymbol()}}</span><span class="pull-right"> {{ $sales->discount }}</span>
                </div>
                <div class="col-xs-5">{{__('Payment')}}: </div>
                <div class="col-xs-7"> 
                    <span class="text-left"> {{currencySymbol()}}</span><span class="pull-right"> {{ $sales->payment }}</span>
                </div>
                <div class="col-xs-5">{{__('Tax')}}: </div><div class="col-xs-7"> <span class="text-left">{{currencySymbol()}}</span><span class="pull-right"> {{ $sales->tax }}</span></div>
                <div class="col-xs-5">{{__('Dues')}}: </div><div class="col-xs-7"> <span class="text-left">{{currencySymbol()}}</span><span class="pull-right"> {{ $sales->dues }}</span></div>
            </div>
        </div>
    </div>
    <hr class="hidden-print"/>
    <div class="row">
        <div class="col-md-6">
            &nbsp;
        </div>
        <div class="col-md-2 col-4">
            <button type="button" onclick="printInvoice()" class="btn btn-info pull-right hidden-print">{{trans('sale.print')}}</button>
        </div>
        <div class="col-md-2 col-4">
            <a href="{{ url('/sales/create') }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('sale.new_sale')}}</a>
        </div>
        <div class="col-md-2 col-4">
            <a href="javascript:;" data-ajax-url="{{ route('sale.mail-invoice', $sales->id) }}" type="button" class="btn btn-info pull-right hidden-print">{{__('Email Invoice')}}</a>
        </div>
    </div>
</section>