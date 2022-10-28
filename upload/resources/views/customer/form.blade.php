@if(!empty($customer))
    <div class="modal-content" id="editCustomer">
    {{ Form::model($customer, array('route' => array('customers.update', $customer->id), 'method' => 'PUT', 'files' => true)) }}
@else
    <div class="modal-content" id="addCustomer">
        {{ Form::open(array('url' => 'customers', 'files' => true,)) }}
@endif
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">@if(!empty($customer)) {{__('Edit Customer')}} @else {{__('Add Customer')}}@endif</h4>
    </div>
    <div class="modal-body" >
       <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    {{ Form::label('name', trans('customer.name') .' *',['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9"> 
                        {{ Form::text('name', null, array('class' => 'form-control', 'required')) }}
                    </div>
                </div>
                <div class="form-group row">
                {{ Form::label('email', trans('customer.email'),['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9">
                        {{ Form::text('email', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="form-group row">
                {{ Form::label('phone_number', __('Phone'), ['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9"> 
                        {{ Form::text('phone_number', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('address', trans('customer.address'),['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9"> 
                        {{ Form::text('address', null, array('class' => 'form-control')) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('city', trans('customer.city'),['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9"> 
                    {{ Form::text('city', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                
            </div>
            <div class="col-sm-6">
                <div class="form-group row">
                    {{ Form::label('state', trans('customer.state'),['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9"> 
                        {{ Form::text('state', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('zip', trans('customer.zip'),['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9"> 
                    {{ Form::text('zip', null, array('class' => 'form-control')) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('prev_balance', __('Balance'), ['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9">
                        @if(!empty($customer))
                            {{ Form::number('prev_balance', null, ['class' => 'form-control']) }}
                            @else
                            {{ Form::number('prev_balance', 0, ['class' => 'form-control']) }}
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('payment', trans('customer.payment') ,['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9">
                        @if(!empty($customer))
                        {{ Form::number('payment', null, ['class' => 'form-control', 'readonly'=>'']) }}
                        @else
                        {{ Form::number('payment', 0, ['class' => 'form-control']) }}
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('avatar', trans('customer.choose_avatar'),['class'=>'col-sm-3 text-right']) }}
                    <div class="col-sm-9">
                    {{ Form::file('avatar', null, array('class' => 'form-control')) }}
                        @if(isset($customer->avatar))
                        <img src="{{$customer->fileUrl()}}" alt="" height="35">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(!empty($page))
        <input type="hidden" name="page" value="{{$page}}" />
        @endif
        {{ Form::submit(trans('customer.submit'), array('class' => 'btn btn-success')) }}
        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
    </div>
    {{ Form::close() }}
</div>