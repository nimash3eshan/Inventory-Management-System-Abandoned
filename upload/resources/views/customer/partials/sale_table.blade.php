<div id="saleTable">
@if(count($salereport))
<table class="table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>{{trans('report-sale.date')}}</th>
      <th>{{trans('report-sale.items_purchased')}}</th>
      <th class="hidden-xs">{{trans('report-sale.sold_by')}}</th>
      @if( $type != 'customer')
      <th class="hidden-xs">{{trans('report-sale.sold_to')}}</th>
      @endif
      <th>{{trans('report-sale.total')}}</th>
      <th class="hidden-xs">{{trans('report-sale.payment')}}</th>
      <th class="hidden-xs">{{trans('report-sale.dues')}}</th>
      <th class="hidden-xs">{{trans('report-sale.payment_type')}}</th>
      <th>{{__('Status')}}</th>
      <th width="60" class="hidden-print">&nbsp;</th>
    </tr>
  </thead>

  <tbody class="list-sale-report">
     @foreach($salereport as $value)
    <tr>
      <td>{{ $value->created_at->format('d M Y') }}</td>
      <td>{{count($value->saleItems)}}</td>
      <td class="hidden-xs">{{ $value->user->name }}</td>
      @if($type != 'customer')
      <td class="hidden-xs">{{ !empty($value->customer) ? $value->customer->name : '' }}</td>
      @endif
      <td>{{currencySymbol().$value->grand_total}}</td>
      <td class="hidden-xs">{{currencySymbol().$value->payment}} </td>
      <td class="hidden-xs">{{currencySymbol().$value->dues}}</td>
      <td class="hidden-xs">{{ $value->payment_type }}</td>
      <td>{!! $value->getStatus() !!}</td>
      <td class="hidden-print">
        <div class="btn-group action-btn-group">
          <button type="button" class="btn action-btn dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-cog"></i><span class="sr-only">Toggle Dropdown</span>
          </button>
          <ul class="dropdown-menu" role="menu">
              <li><a data-toggle="collapse" href="#detailedSales{{ $value->id }}" aria-expanded="false" aria-controls="detailedReceivings"><i class="fa fa-eye"></i> {{trans('report-sale.detail')}}</a></li>
              @if(auth()->user()->checkSpPermission('sale.refund'))
              <li><a href="{{route('sale.refund', $value->id)}}" target="_blank"><i class="fa fa-undo" aria-hidden="true"></i>{{__('Refund')}}</a></li>
              @endif
              @if(auth()->user()->checkSpPermission('sale.edit'))
              <li><a href="{{route('sale.edit', $value->id)}}" target="_blank"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></li>
              @endif
              @if(auth()->user()->checkSpPermission('sale.show-invoice'))
              <li><a href="{{route('sale.show-invoice', $value->id)}}" target="_blank" ><i class="fa fa-file-text-o"></i> {{__('Show Invoice')}}</a></li>
              @endif
              @if($value->status == \App\Sale::DUE)
              <li><a href="#" data-toggle="modal" data-target="#paymentModal{{$value->id}}" ><i class="fa fa-money"></i>  {{__('Payment')}}</a></li>
              @endif
          </ul>
          <div class="modal submodal fade" id="paymentModal{{$value->id}}" role="dialog">
            <div class="modal-dialog modal-sm">
              <div class="modal-content text-left">
                <div class="modal-header">
                  <button type="button" class="close" onclick="closeEl('.submodal', '#paymentModal{{$value->id}}')">&times;</button>
                  <h4 class="modal-title">{{__('Add Payment')}}</h4>
                </div>
                <div class="modal-body">
                  {{ Form::open(['route'=>'salepayments.store']) }}
                  <div class="form-group">
                    {{ Form::select('payment_type', $payment_types, null, array('class' => 'form-control','placeholder'=>'Select a payment type','required')) }}
                  </div>
                  <div class="form-group"><!---select account input-->
                    {{Form::select('account_id', $accounts, null, ['class'=>'form-control', 'placeholder'=>'Select Account', 'required'])}}
                  </div>
                  <div class="form-group">
                    {{ Form::hidden('sale_id', $value->id, ['class'=>'form-control']) }}
                    {{ Form::number('payment', null, ['class'=>'form-control', 'placeholder'=>'Amount', 'min'=>0, 'required']) }}
                  </div>
                <div class="form-group">
                    {{ Form::text('comments', null, ['class'=>'form-control','placeholder'=>'Comments']) }}
                </div>
                  <div class="form-group">
                    @if($type == 'customer')
                    <input type="hidden" name="action_page" value="customer">
                    {{ Form::submit('Add Payment', ['class'=>'btn btn-success','onclick'=>"$('.modal-backdrop').remove();"]) }}
                    @else
                    <input type="hidden" name="action_page" value="sale_list">
                    {{ Form::submit('Add Payment', ['class'=>'btn btn-success', 'onclick'=>"$('.modal-backdrop').remove();$('body').removeClass('modal-open');"]) }}
                    @endif
                  </div>
                  {{ Form::close() }}
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success"  onclick="closeEl('.submodal', '#paymentModal{{$value->id}}')">{{__('Close')}}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </td>
    </tr>

    <tr class="collapse" id="detailedSales{{ $value->id }}">
      <td colspan="10">
          <table class="table">
              <tr>
                  <td>{{trans('report-sale.item_id')}}</td>
                  <td>{{trans('report-sale.item_name')}}</td>
                  <td>{{trans('report-sale.quantity_purchase')}}</td>
                  <td>{{trans('report-sale.total')}}</td>
                  <td>{{trans('report-sale.profit')}}</td>
              </tr>
              @foreach($value->saleItems as $SaleDetailed)
                  <tr>
                      <td>{{ $SaleDetailed->item_id }}</td>
                      <td>{{ $SaleDetailed->item->item_name }}</td>
                      <td>{{ $SaleDetailed->quantity }}</td>
                      <td>{{currencySymbol()}}{{ $SaleDetailed->selling_price * $SaleDetailed->quantity}}</td>
                      <td>{{currencySymbol()}}{{ ($SaleDetailed->quantity * $SaleDetailed->selling_price) - ($SaleDetailed->quantity * $SaleDetailed->cost_price)}}</td>
                  </tr>
              @endforeach
          </table>
      </td>
    </tr>
   @endforeach
  </tbody>
</table>

<!-- /.post -->
@if($type != 'datefilter')
@php if($type == 'customer') { 
  $index_route = route('customers.show', $customer->id);
} else {
  $index_route = url('/sales');
} @endphp
@include('partials.pagination', ['items'=>$salereport, 'index_route'=>$index_route])
@endif
@endif
</div>