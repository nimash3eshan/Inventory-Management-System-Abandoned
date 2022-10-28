<div id="list_stock_report">
<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th class="hidden-xs">{{__('UPC/EAN/ISBN')}}</th>
            <th>{{__('Item Name')}}</th>
            <th>{{__('Size')}}</th>
            <th>{{__('In Qty')}}</th>
            <th>{{__('Out Qty')}}</th>
            <th>{{__('Current Qty')}}</th>
            <th class="hidden-xs">{{__('Expire Date')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td class="hidden-xs">{{$item->upc_ean_isbn}}</td>
            <td>{{$item->item_name}}</td>
            <td>{{$item->size}}</td>
            <td>{{!empty($stock_reports[$item->id]['in']) ? $stock_reports[$item->id]['in'] : 0}}</td>
            <td>{{!empty($stock_reports[$item->id]['out']) ? $stock_reports[$item->id]['out'] : 0}}</td>
            <td>{{$item->quantity}}</td>
            <td class="hidden-xs">{{!empty($item->expire_date) ? date('d M Y', strtotime($item->expire_date)) : ""}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@include('partials.pagination', ['items'=>$items, 'index_route'=>route('report.stock')])
</div>