@extends('layouts.admin_dynamic')

@section('content')
      <div class="modal-content" id="CategoryForm">
        {{ Form::open(['route' => 'itemattribute.store', 'method' => 'post']) }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{__('Add Item Attribute')}}</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              {{ Form::label('item_id', __('Item ID')) }}
              {{ Form::text('item', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
              {{ Form::label('name', __('Name')) }}
              {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
              {{ Form::label('exp_date', __('Expired Date')) }}
              {{ Form::text('exp_date', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
              {{ Form::label('quantity', __('Quantity')) }}
              {{ Form::text('quantity', null, array('class' => 'form-control')) }}
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">{{__('Create')}}</button>
          </div>
          {{ Form::close() }}
        </div>
@endsection