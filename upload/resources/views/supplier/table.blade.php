<div id="supplierTable">
<table id="" class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>{{trans('supplier.company_name')}}</td>
            <td class="hidden-xs">{{trans('supplier.name')}}</td>
            <td class="hidden-xs">{{trans('supplier.email')}}</td>
            <td>{{trans('supplier.phone_number')}}</td>
            <td class="hidden-xs">{{trans('supplier.avatar')}}</td>
            <td>&nbsp;</td>
        </tr>
    </thead>
    <tbody>
    @foreach($suppliers as $value)
        <tr>
            <td>{{ $value->company_name }}</td>
            <td class="hidden-xs">{{ $value->name }}</td>
            <td class="hidden-xs">{{ $value->email }}</td>
            <td>{{ $value->phone_number }}</td>
            <td class="hidden-xs"><img src="{{$value->fileUrl()}}" alt="" height="30"></td>
            <td class="item_btn_group">
                @php
                $actions = [
                  ['data-replace'=>'#showSupplier','url'=>'#showSupplierModal','ajax-url'=>url('suppliers/' . $value->id . '/'), 'name'=>'View Profile', 'icon'=>'eye'],
                  ['data-replace'=>'#editSupplier','url'=>'#editSupplierModal','ajax-url'=>url('suppliers/'.$value->id.'/edit'), 'name'=>trans('item.edit'), 'icon'=>'pencil'],
                  ['url'=>'suppliers/' . $value->id,'name'=>'delete']];
                @endphp
                @include('partials.actions', ['actions'=>$actions])
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@include('partials.pagination', ['items'=>$suppliers, 'index_route'=>route('suppliers.index')])
</div>