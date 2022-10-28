<div class="" id="itemTable">
    <table class="table table-bordered table-striped table-responsive" id="myTable1">
        <thead>
            <tr>
                <th class="hidden-xs">{{__('SKU')}}</th>
                <th>{{trans('item.item_name')}}</th>
                <th class="hidden-xs">{{__('Category')}}</th>
                <th class="hidden-xs">{{trans('item.size')}}</th>
                <th class="hidden-xs">{{trans('item.cost_price')}}</th>
                <th>{{trans('item.selling_price')}}</th>
                <th class="hidden-xs">{{trans('item.avatar')}}</th>
                <th class="text-center">{{__('Action')}}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $value )
            <tr>
                <td class="hidden-xs">{{ $value->upc_ean_isbn }}</td>
                <td>{{ $value->item_name }}</td>
                <td>{{ !empty ($value->category) ? $value->category->name : '' }}</td>
                <td class="hidden-xs">{{ $value->size }}</td>
                <td class="hidden-xs">{{ $value->cost_price }}</td>
                <td>{{ $value->selling_price }}</td>
                <td class="hidden-xs"><img src="{{$value->fileUrl()}}" alt="" height="30"></td>
                <td class="item_btn_group">
                    @php
                    $actions = [
                    ['data-replace'=>'#inventory','url'=>'#inventoryModal','ajax-url'=>url('inventory/'. $value->id .'/edit'), 'name'=>trans('item.inventory'), 'icon'=>'list'],
                    ['data-replace'=>'#item_attribute','url'=>'#itemAttributeModal','ajax-url'=>url('itemattribute?item_id='. $value->id), 'name'=>trans('item.Variants'), 'icon'=>'book'],
                    ['data-replace'=>'#editItem','url'=>'#editItemModal','ajax-url'=>url('items/'.$value->id. '/edit'), 'name'=>trans('item.edit'), 'icon'=>'pencil'],
                    ['url'=>'items/'. $value->id, 'name'=>'delete']];
                    @endphp
                    @include('partials.actions', ['actions'=>$actions])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('partials.pagination', ['items'=>$items, 'index_route'=>route('items.index')])
  </div>
<!-- /.box-body -->