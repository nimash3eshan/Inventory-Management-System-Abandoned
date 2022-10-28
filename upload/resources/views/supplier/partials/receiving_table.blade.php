<div id="receivingTable">
 @if(count($receivingreport))
 <table class="table table-striped table-bordered table-hover" id="myTable1">
     <thead>
     <tr>
       <th>{{trans('report-receiving.date')}}</th>
       <th>{{__('Items')}}</th>
       <th class="hidden-xs hidden-sm">{{trans('report-receiving.received_by')}}</th>
       @if( $type != 'supplier')
       <th class="hidden-xs hidden-sm">{{trans('report-receiving.supplied_by')}}</th>
       @endif
       <th class="hidden-xs">{{trans('report-receiving.total')}}</th>
       <th class="hidden-xs">{{trans('report-receiving.payment_type')}}</th>
       <th>{{trans('report-receiving.payment')}}</th>
       <th>{{trans('report-receiving.dues')}}</th>
       <th>{{__('Status')}}</th>
       <th width="50" class="hidden-print">&nbsp;</th>
     </tr>
     </thead>

     <tbody class="list-sale-report">

     @foreach($receivingreport as $value)
        <tr>
            <td>{{ $value->created_at->format('M d, Y') }}</td>
            <td>{{count($value->receivingItems)}}</td>
            <td class="hidden-xs hidden-sm">{{ $value->user->name }}</td>
            @if( $type != 'supplier')
            <td class="hidden-xs hidden-sm">{{ !empty($value->supplier->company_name) ? $value->supplier->company_name : "" }}</td>
            @endif
            <td class="hidden-xs">{{currencySymbol().number_format($value->receivingItems->sum('total_cost'), 2)}}</td>
            <td class="hidden-xs">{{ $value->payment_type }}</td>
            <td>{{ currencySymbol().$value->payment }}</td>
            <td>{{ currencySymbol().$value->dues }}</td>
            <td>{!! $value->getStatus() !!}</td>
            <td class="hidden-print">
                <div class="btn-group action-btn-group">
                    <button type="button" class="btn action-btn dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-cog"></i>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a data-toggle="collapse" href="#detailedReports{{ $value->id }}" aria-expanded="false" aria-controls="detailedReceivings"><i class="fa fa-list"></i>  {{trans('report-sale.detail')}}</a></li>
                      @if(auth()->user()->checkSpPermission('receivings.show-invoice'))
                      <li><a href="{{route('receivings.show-invoice', $value->id)}}" target="_blank" ><i class="fa fa-file-text-o"></i> {{__('Show Invoice')}}</a></li>
                      @endif
                      @if($value->dues > 0 || $type == 'dues')
                      <li><a href="#" data-toggle="modal" data-target="#myModal{{$value->id}}"><i class="fa fa-money"></i>  {{__('Payment')}}</a></li>
                      @endif
                    </ul>
                    <div class="modal fade" id="myModal{{$value->id}}" role="dialog">
                      <div class="modal-dialog modal-sm">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">{{__('Add Payment')}}</h4>
                            </div>
                            <div class="modal-body">
                              {{ Form::open(['route'=>'receivingpayments.store']) }}
                              <div class="form-group">
                                {{ Form::select('payment_type', $payment_types, null, array('class' => 'form-control','placeholder'=>'Select a payment type','required')) }}
                              </div>
                              <div class="form-group"><!---select account input-->
                                {{Form::select('account_id', $accounts, null, ['class'=>'form-control', 'placeholder'=>'Select Account', 'required'])}}
                              </div>
                              <div class="form-group">
                                {{ Form::hidden('receiving_id', $value->id, ['class'=>'form-control']) }}
                                {{ Form::number('payment', null, ['class'=>'form-control', 'min'=>0, 'placeholder'=>'Amount', 'required']) }}
                              </div>
                              <div class="form-group">
                                {{ Form::text('comments', null, ['class'=>'form-control','placeholder'=>'Comments']) }}
                              </div>
                              <div class="form-group">
                                @if($type == 'supplier')
                                <input type="hidden" name="action_page" value="supplier">
                                {{ Form::submit('Add Payment', ['class'=>'btn btn-success','onclick'=>"$('.modal-backdrop').remove();"]) }}
                                @else
                                <input type="hidden" name="action_page" value="receiving_list">
                                {{ Form::submit('Add Payment', ['class'=>'btn btn-success', 'onclick'=>"$('.modal-backdrop').remove();$('body').removeClass('modal-open');"]) }}
                                @endif
                              </div>
                              {{ Form::close() }}
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-success" data-dismiss="modal">{{__('Close')}}</button>
                            </div>
                          </div>
                      </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="collapse" id="detailedReports{{ $value->id }}">
          <td colspan="9">
            <table class="table">
              <tr>
                  <td>{{trans('report-receiving.item_id')}}</td>
                  <td>{{trans('report-receiving.item_name')}}</td>
                  <td>{{trans('report-receiving.item_received')}}</td>
                  <td>{{trans('report-receiving.total')}}</td>
              </tr>
              @foreach($value->receivingItems as $receiving_detailed)
              <tr>
                <td>{{ $receiving_detailed->item_id }}</td>
                <td>{{ $receiving_detailed->item->item_name }}</td>
                <td>{{ $receiving_detailed->quantity }}</td>
                <td>{{ currencySymbol().$receiving_detailed->quantity * $receiving_detailed->cost_price}}</td>
              </tr>
              @endforeach
            </table>
          </td>
      </tr>
     @endforeach

     </tbody>
 </table>

@php if($type == 'supplier') { 
  $index_route = route('suppliers.show', $supplier->id);
} else {
  $index_route = url('/receivings');
} @endphp
@include('partials.pagination', ['items'=>$receivingreport, 'index_route'=>$index_route])
@endif
</div>
