<div class="modal-content" id="item_attribute">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">{{trans('item.itemVariant')}}</h4>
    </div>
    <div class="modal-body" >
        <div class="box box-success">
            <div class="box-body">
                @include('item.editAttributeForm', ['attribute'=>$attribute])
                <div class="box-body">
                  <div class="row py-4">
                    @if ($attributes->count() > 0)
                    <h3>{{trans('item.Variants')}}</h3>
                  <table class="table table-bordered table-striped table-responsive" id="myTable1">
                    <thead>
                      <tr>
                        <th class="hidden-xs">Name</th>
                        <th class="hidden-xs">SKU</th>
                        <th class="hidden-xs">Cost Price</th>
                        <th class="hidden-xs">Selling Price</th>
                        <th class="hidden-xs">Expired Date</th>
                        <th class="hidden-xs">Quantity</th>
                        <th class="hidden-xs">Status</th>
                        <th class="hidden-xs">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($attributes as  $value) 
                      <tr>
                      <td class="hidden-xs">{{$value->name}}</td>
                      <td class="hidden-xs">{{$value->sku}}</td>
                      <td class="hidden-xs">{{$value->cost_price}}</td>
                      <td class="hidden-xs">{{$value->selling_price}}</td>
                      <td class="hidden-xs">{{$value->exp_date}}</td>
                      <td class="hidden-xs">{{$value->quantity}}</td>
                      <td>
                        <label class="switch">
                          <input type="checkbox" name="status" submit-toggle="#changeStatus{{$value->id}}"
                          {{ $value->status == '1' ? 'checked' : ''}}>
                          <span class="slider round"></span>
                        </label>
                        <form action="{{route('itemattribute.update', $value->id)}}" method="POST"  id="changeStatus{{$value->id}}">
                          @csrf
                          @method('PUT')
                          <input  type="hidden" name="status" value="{{ $value->status == 1 ? 0 : 1}}" />
                        </form>
                      </td>
                      <td class="item_btn_group">
                        @php
                        $actions = [
                          ['url'=>'#add','ajax-url'=>url('itemattribute/'.$value->id. '/edit?item_id='.$item->id), 'name'=>trans('item.edit'), 'icon'=>'pencil'],
                        ['url'=>'itemattribute/'.$value->id, 'name'=>'delete']
                      ];
                        @endphp
                        @include('partials.actions', ['actions'=>$actions])
                      </td>
                      </tr>  
                      @endforeach
                    </tbody>
                  </table>
                    @endif
                  </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>    
</div>