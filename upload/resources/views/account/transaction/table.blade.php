<div id="transactionTable">
    <table id="myTableTransaction" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>{{__('Created at')}}</th>
            <th>{{__('Transaction Type')}}</th>
            <th>{{__('Amount')}}</th>
            <th class="hidden-xs">{{__('Company')}}</th>
            <th class="hidden-xs">{{__('Created By')}}</th>
            <th class="text-center">{{__('Action')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $value)
            <tr>
                <td>{{ $value->created_at->format('d M, Y') }}</td>
                @if($value->transaction_type == 1)
                <td>{{__('Payment')}}</td>
                @elseif($value->transaction_type == 2)
                <td>{{__('Receipt')}}</td>
                @else
                <td>{{__('Charge')}}</td>
                @endif
                <td>{{ $value->amount}}</td>
                <td class="hidden-xs">{{ $value->account->company }}</td>
                <td class="hidden-xs">{{$value->user->name}}</td>
                <td class="item_btn_group">
                    @php
                    $actions = [
                      ['data-replace'=>'#editTransaction','url'=>'#editTransactionModal','ajax-url'=>url('transactions/'.$value->id.'/edit'), 'name'=>trans('item.edit'), 'icon'=>'pencil'],
                      ['url'=>'transactions/' . $value->id,'name'=>'delete']];
                    @endphp
                    @include('partials.actions', ['actions'=>$actions])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('partials.pagination', ['items'=>$transactions, 'index_route'=>route('transactions.index')])
</div>