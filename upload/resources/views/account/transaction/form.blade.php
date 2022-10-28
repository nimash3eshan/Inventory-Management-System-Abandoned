@if(!empty($transaction))
<div class="modal-content" id="editTransaction">
    {{ Form::model($transaction, array('route' => array('transactions.update', $transaction->id), 'method' => 'PUT', 'files' => true,)) }}
@else
<div class="modal-content" id="addTransaction">
    {{ Form::open(array('url' => 'transactions', 'files' => true,)) }}
@endif
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">@if(!empty($transaction)) {{__('Edit Transaction')}} @else {{__('Add Transaction')}}@endif</h4>
</div>
<div class="modal-body" >
    <div class="row">
        <div class="col-md-6" >
            <div class="form-group row">
                {{ Form::label('transaction_type', __('Transaction Type') .' *',['class'=>'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                    {{ Form::select('transaction_type', ['1'=>'Payment','2'=>'Receipt','3'=>'Charge'],null, array('class' => 'form-control','required')) }}
                </div>
            </div>

            <div class="form-group row">
                {{ Form::label('account_id', __('Accounts') .' *', ['class'=>'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                    {{ Form::select('account_id', $accounts, null, array('class' => 'form-control', 'placeholder'=>'Select an account','required')) }}
                </div>
            </div>
            
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                {{ Form::label('transaction_with', __('Transaction with') .' *', ['class'=>'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                    {{ Form::text('transaction_with', null, array('class' => 'form-control', 'placeholder'=>'Payee/Receipient name','required')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('amount', __('Amount') .' *',['class'=>'col-sm-4 text-right']) }}
                <div class="col-sm-8">
                    {{ Form::number('amount', null, array('class' => 'form-control','required')) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    {{ Form::submit(__('Submit'), array('class' => 'btn btn-success')) }}
    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
</div>
{{ Form::close() }}
</div>