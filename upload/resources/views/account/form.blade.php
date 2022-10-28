@if(!empty($account))
<div class="modal-content" id="editAccount">
{{ Form::model($account, array('route' => array('accounts.update', $account->id), 'method' => 'PUT', 'files' => true)) }}
@else
<div class="modal-content" id="addAccount">
    {{ Form::open(array('url' => 'accounts', 'files' => true,)) }}
@endif
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">@if(!empty($account)) {{__('Edit Account')}} @else {{__('Add Account')}}@endif</h4>
</div>
<div class="modal-body" >
    <div class="row">
        <div class="col-md-6" >
            <div class="form-group row">
                {{ Form::label('name', __('Name') .' *',['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{ Form::text('name', null, array('class' => 'form-control', 'required')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('company', __('Bank/Company').' *',['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{ Form::text('company', null, array('class' => 'form-control', 'required')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('branch_name', __('Branch'), ['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{ Form::text('branch_name', null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('account_no', __('Account No'),['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{ Form::number('account_no', null, array('class' => 'form-control')) }}
                </div>
            </div>
            
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                {{ Form::label('email', __('Email'),['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{ Form::text('email', null, array('class'=>'form-control')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('pin', __('Pin'),['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{ Form::number('pin', null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('balance', __('Balance').' *',['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                    {{ Form::number('balance', null, array('class' => 'form-control', 'required')) }}
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