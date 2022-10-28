<section class="content panel print-content" id="receivingContent">
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
            {{trans('receiving.supplier')}}: {{ $receivings->supplier->name}}<br />
            @if(!empty($receivings->supplier->address))
            {{trans('supplier.address')}}: {{ $receivings->supplier->address}}<br />
            @endif
            {{trans('Purchase ID')}}: {{$receivings->invoice_no}}<br />
        </div>
        <div class="col-md-5 col-sm-5 col-xs-6 text-right">
            {{__('Date')}} : {{ Carbon\Carbon::now() }}<br>
            @if(!empty($receivings->supplier->phone_number))
            {{trans('supplier.phone_number')}} : {{$receivings->supplier->phone_number}}<br>
            @endif
            {{trans('sale.employee')}}: {{$receivings->user->name}}<br />
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive font-size-15">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td>{{trans('sale.item')}}</td>
                            <td>{{trans('sale.price')}}</td>
                            <td>{{trans('sale.qty')}}</td>
                            <td align="right">{{trans('sale.total')}}</td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($receivingItems as $value)
                        <tr>
                            <td>{{$value->item->item_name}}{{!empty($value->attribute_id) ? ' > '.$value->attribute->name : ''}}</td>
                            <td>{{$value->cost_price}}</td>
                            <td>{{$value->quantity}}</td>
                            <td align="right">{{$value->total_cost}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row print_footer pt-2 font-size-15">
        <div class="col-md-7 col-sm-7 col-xs-6">
            {{trans('sale.payment_type')}}: {{$receivings->payment_type}}
        </div>
        <div class="col-md-2 col-sm-1"></div>
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="row invoice-footer">
                <div class="col-xs-4">{{__('Total')}}: </div><div class="col-xs-8"> <span class="text-left">{{currencySymbol()}}</span><span class="pull-right"> {{ $receivings->total }}</span></div>
                
                <div class="col-xs-4">{{__('Payment')}}: </div>
                <div class="col-xs-8"> 
                    <span class="text-left"> {{currencySymbol()}}</span><span class="pull-right"> {{ $receivings->payment }}</span>
                </div>
                <div class="col-xs-4">{{__('Dues')}}: </div><div class="col-xs-8"> <span class="text-left">{{currencySymbol()}}</span><span class="pull-right"> {{ $receivings->dues }}</span></div>
            </div>
        </div>
    </div>
    <hr class="hidden-print"/>
    <div class="row">
        <div class="col-md-8">
            &nbsp;
        </div>
        <div class="col-md-2">
            <button type="button" onclick="printInvoice()" class="btn btn-info pull-right hidden-print">{{trans('sale.print')}}</button>
        </div>
        <div class="col-md-2">
            <a href="{{ url('/receivings/create') }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('receiving.new_receiving')}}</a>
        </div>
    </div>
</section>