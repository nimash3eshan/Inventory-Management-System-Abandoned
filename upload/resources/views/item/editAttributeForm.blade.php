<div class="row" id="editAttributeForm">
    @if(!empty($attribute))
        {{ Form::model($attribute, array('route' => array('itemattribute.update', $attribute->id), 'method' => 'PUT', 'files' => true)) }}
    @else
        {{ Form::open(['url' => route('itemattribute.store'), 'files' => true]) }}
    @endif
    {{ Form::hidden('item_id', $item->id, array() )}}
      <div class="form-group col-sm-12">
       
        @if(!empty($attribute))
        <h4 class="mb-4">Edit attribute : {{$attribute->name}}</h4>
        @else
        <h4 class="mb-4">Create new attribute</h4>
        @endif
      </div>
    <div class="col-sm-6">
        <div class="form-group row">
            {{ Form::label('name', __('Item'), ['class'=>'col-sm-3 text-right pr-0'])}}
            <div class="col-sm-9"> 
            {{ Form::text('name', $item->item_name, array('class' => 'form-control','required', 'disabled')) }}
            </div>
        </div>
      <div class="form-group row">
        {{ Form::label('name', __('Name'), ['class'=>'col-sm-3 text-right pr-0'])}}
        <div class="col-sm-9"> 
        {{ Form::text('name', null, array('class' => 'form-control','required')) }}
        </div>
      </div>
      <div class="form-group row">
        {{ Form::label('sku', __('SKU'), ['class'=>'col-sm-3 text-right pr-0']) }}
        <div class="col-sm-9"> 
        {{ Form::text('upc_ean_isbn', null, array('class' => 'form-control','required')) }}
      </div>
      </div>
      <div class="form-group row">
        {{ Form::label('cost_price', __('Cost Price'), ['class'=>'col-sm-3 text-right pr-0']) }}
        <div class="col-sm-9"> 
        {{ Form::text('cost_price', null, array('class' => 'form-control','required')) }}
        </div>
      </div>
    </div>
    <div class="col-sm-6 pl-0">
      <div class="form-group row">
        {{ Form::label('selling_price', __('Selling Price'), ['class'=>'col-sm-3 pr-0 text-right']) }}
        <div class="col-sm-9"> 
        {{ Form::text('selling_price', null, array('class' => 'form-control','required')) }}
        </div>
      </div>
      <div class="form-group row">
        {{ Form::label('exp_date', __('Expired Date'), ['class'=>'col-sm-3 pr-0 text-right']) }}
        <div class="col-sm-9"> 
        {{ Form::date('exp_date', null, array('class' => 'form-control','required')) }}
        </div>
      </div>
      <div class="form-group row">
        {{ Form::label('quantity', __('Quantity'), ['class'=>'col-sm-3 pr-0 text-right']) }}
        <div class="col-sm-9"> 
        {{ Form::text('quantity', null, array('class' => 'form-control','required')) }}
        </div>
      </div>
      <div class="form-group row">
        {{ Form::label('image', __('Image:'), ['class'=>'col-sm-3 pr-0 text-right']) }}
        <div class="col-sm-9"> 
        {{ Form::file('image', null, ['class' => 'form-control']) }}
        @if(!empty($attribute))
        <img src="{{$attribute->fileUrl('image')}}" alt="" height="35">
        @endif
        </div>
    </div>
    </div>
    <div class="col-sm-12 text-right"> 
        @if(!empty($attribute))
        {{ Form::submit(__('Update'), ['class' => 'btn btn-success']) }}
        <a href="#add" data-ajax-url="{{url('itemattribute?item_id='. $item->id)}}" class="btn btn-info">Create new </a>
        @else
        {{ Form::submit(trans('item.submit'), ['class' => 'btn btn-success']) }}
        @endif
    </div>
        {{ Form::close() }}
</div>