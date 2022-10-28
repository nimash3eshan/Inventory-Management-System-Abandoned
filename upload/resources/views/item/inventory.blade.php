<div class="modal-content" id="inventory">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">{{__('Inventory')}}</h4>
    </div>
    <div class="modal-body" >
        <div class="box box-success">
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th>{{__('UPC/EAN/ISBN')}}</td>
                        <th>{{trans('item.item_name')}}</td>
                        <th>{{trans('item.current_quantity')}}</td>
                    </tr>
                    <tr>
                        <td>{{ $item->upc_ean_isbn }}</td>
                        <td>{{ $item->item_name }}</td>
                        @if($attributes->count() > 0)
                            <td>{{" "}}</td>
                        @else
                            <td>{{ $item->quantity }}</td>
                        @endif
                        
                    </tr>
                </table><br>
                @if($attributes->count() > 0)
                <h4 class="modal-title">{{__('Attributes')}}</h4><br>
                <table class="table table-bordered">
                    <tr>
                        <th>{{trans('Attribute Name')}}</td>
                        <th>{{trans('Quantity')}}</td>
                    </tr>
                    @foreach ($attributes as $attribute)
                    <tr>
                        <td>{{$attribute->name}}</td>
                        <td>{{$attribute->quantity}}</td>
                    </tr>
                    @endforeach
                </table>

                <div class="row" style="padding: 20px 0;">
                    {{ Form::model($item->inventory, ['route' => ['inventory.update', $item->id], 'method' => 'PUT']) }}

                    <div class="form-group row" id="addCategoryBtn">
                        {{ Form::label('Attributes', __('Attributes') .' *',['class'=>'col-sm-3 text-right']) }}
                        <div class="col-sm-7 no-margin no-right-padding">
                        {{ Form::select('attribute_id', $attributep, null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    @else
                    <div class="row" style="padding: 20px 0;">
                    {{ Form::model($item->inventory, ['route' => ['inventory.update', $item->id], 'method' => 'PUT']) }}
                    @endif

                    <div class="col-sm-3 text-right pt-1">
                        {{trans('item.inventory_to_add_subtract')}} *
                    </div>
                    <div class="col-sm-2">
                        {{ Form::text('in_out_qty', null, ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="col-sm-1 pt-1">
                        {{trans('item.comments')}}
                    </div>
                    <div class="col-sm-4">
                        {{ Form::text('remarks', null, ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="col-sm-1"> 
                        {{ Form::submit(trans('item.submit'), ['class' => 'btn btn-success']) }}
                    </div>
                        {{ Form::close() }}
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        @include('inventory.partials.inventory_table', ['inventory'=>$sal_inv, 'title'=>'Sale Inventory'])
        @include('inventory.partials.inventory_table', ['inventory'=>$rec_inv, 'title'=>'Receive Inventory'])
        @include('inventory.partials.inventory_table', ['inventory'=>$other_inv, 'title'=>'Other Inventory'])
    </div>    
</div>