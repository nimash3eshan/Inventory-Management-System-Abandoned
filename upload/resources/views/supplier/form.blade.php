@if(!empty($supplier))
<div class="modal-content" id="editSupplier">
    {{ Form::model($supplier, array('route' => array('suppliers.update', $supplier->id), 'method' => 'PUT', 'files' => true)) }}
@else
<div class="modal-content" id="addSupplier">
    {{ Form::open(array('url' => 'suppliers', 'files' => true)) }}
@endif
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">@if(!empty($supplier)) {{__('Edit Supplier')}} @else {{__('Add Supplier')}}@endif</h4>
</div>
<div class="modal-body" >
    <div class="row">
        <div class="col-md-6"> 
            <div class="form-group row">
                {{ Form::label('company_name', trans('supplier.company_name').' *', ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('company_name', Null, array('class' => 'form-control', 'required')) }}
                </div>
            </div>

            <div class="form-group row">
                {{ Form::label('name', trans('supplier.name').' *', ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('name', Null, array('class' => 'form-control')) }}
                </div>
            </div>

            <div class="form-group row">
                {{ Form::label('email', trans('supplier.email'), ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('email', Null, array('class' => 'form-control')) }}
                </div>
            </div>

            <div class="form-group row">
                {{ Form::label('phone_number', trans('supplier.phone_number'), ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('phone_number', Null, array('class' => 'form-control')) }}
                </div>
            </div>

            <div class="form-group row">
                {{ Form::label('address', trans('supplier.address'), ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('address', Null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('city', trans('supplier.city'), ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('city', Null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group hidden-xs row">
                {{ Form::label('state', trans('supplier.state'), ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('state', Null, array('class' => 'form-control')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group hidden-xs row">
                {{ Form::label('zip', trans('supplier.zip'), ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('zip', Null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group hidden-xs row">
                {{ Form::label('comments', trans('supplier.comments'), ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('comments', Null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('account', trans('supplier.account').' #', ['class'=> 'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                {{ Form::text('account', Null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('prev_balance', trans('supplier.prev_balance'),['class'=>'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                    @if(isset($supplier))
                {{ Form::number('prev_balance', null, ['class' => 'form-control']) }}
                    @else
                {{ Form::number('prev_balance', 0, ['class' => 'form-control']) }}
                        @endif
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('payment', trans('supplier.payment') ,['class'=>'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                    @if(!empty($supplier))
                {{ Form::number('payment', null, ['class' => 'form-control', 'readonly'=>'']) }}
                    @else
                {{ Form::number('payment', 0, ['class' => 'form-control']) }}
                        @endif
                </div>
            </div>
            <div class="form-group row">
            {{ Form::label('avatar', trans('supplier.choose_avatar'), ['class'=> 'col-sm-4 text-right']) }}
            <div class="col-sm-8">
            {{ Form::file('avatar', Null, array('class' => 'form-control')) }}
                @if(!empty($supplier->avatar))
                <img src="{{$supplier->fileUrl()}}" alt="" height="35">
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    @if(!empty($page))
    <input type="hidden" name="page" value="{{$page}}">
    @endif
    {{ Form::submit(trans('supplier.submit'), array('class' => 'btn btn-success')) }}
    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
</div>
{{ Form::close() }}
</div>