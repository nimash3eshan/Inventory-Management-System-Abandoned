@if(!empty($expense))
<div class="modal-content" id="editExpense"  >
    {{ Form::model($expense, array('route' => array('expense.update', $expense->id), 'method' => 'PUT', 'files' => true,)) }}
@else
<div class="modal-content" id="addExpense">
    {{ Form::open(array('url' => 'expense', 'files' => true,)) }}
@endif
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">@if(!empty($expense)) {{__('Edit Expense')}} @else {{__('Add Expense')}}@endif</h4>
</div>
<div class="modal-body" >
    <div class="row">
        <div class="col-md-6" >
            @include('expense.add_category_btn')
            <div class="form-group row">
                {{ Form::label('description', __('Description') .' *',['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9"> 
                    {{ Form::text('description', null, array('class' => 'form-control', 'required')) }}
                </div>
            </div>

            <div class="form-group row">
                {{ Form::label('account_id', __('Select Account'),['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{Form::select('account_id', $accounts, null, ['class'=>'form-control'])}}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                {{ Form::label('type', __('Payment Type'),['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9"> 
                {{ Form::select('payment_type', $payment_types, null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('unit_price', __('Amount'), ['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{ Form::number('unit_price', null, array('class' => 'form-control', 'id'=>'unit_price')) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    {{ Form::submit(trans('supplier.submit'), array('class' => 'btn btn-success')) }}
    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
</div>
{{ Form::close() }}

</div>