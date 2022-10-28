<div id="expenseTable">
@if(!empty($expenses))
<table id="myTableExpense" class="table table-bordered table-hover table-striped table-responsive">
    <thead>
        <tr>
            <th>{{__('Created at')}}</th>
            <th class="hidden-xs">{{__('Qty')}}</th>
            <th class="hidden-xs">{{__('Unit Price')}}</th>
            <th>{{__('Total')}}</th>
            <th>{{__('Payment')}}</th>
            <th class="hidden-xs">{{__('Dues')}}</th>
            <th class="hidden-xs">{{__('Category')}}</th>
            <th width="50" class="hidden-xs">{{__('Type')}}</th>
            <th class="hidden-xs">{{__('Created By')}}</th>
            <th class="text-center">{{__('Action')}}</th>
        </tr>
    </thead>
    <tbody>
      @foreach($expenses as $value)
      <tr>
        <td>{{ $value->created_at->format('d M, Y') }}</td>
        <td class="hidden-xs">{{ $value->qty }}</td>
        <td class="hidden-xs">{{ $value->unit_price }}</td>
        <td>{{ $value->total }}</td>
        <td>{{$value->payment}}</td>
        <td class="hidden-xs">{{$value->dues}}</td>
        <td class="hidden-xs">{{$value->expense_category->name}}</td>
        <td class="hidden-xs">{{$value->payment_type}}</td>
        <td class="hidden-xs">{{$value->user->name}}</td>
        <td class="item_btn_group">
            @php
            $actions = [
              ['data-replace'=>'#editExpense','url'=>'#editExpenseModal','ajax-url'=>url('expense/'.$value->id.'/edit'), 'name'=>trans('item.edit'), 'icon'=>'pencil'],
              ['url'=>'expense/' . $value->id,'name'=>'delete']];
            @endphp
            @include('partials.actions', ['actions'=>$actions])
        </td>
      </tr>
      @endforeach
  </tbody>
</table>
@include('partials.pagination', ['items'=>$expenses, 'index_route'=>$index_route])
@endif

</div>