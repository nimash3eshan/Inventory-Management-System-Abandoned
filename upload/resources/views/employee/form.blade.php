@if(!empty($employee))
<div class="modal-content" id="editEmployee">
{{ Form::model($employee, array('route' => array('employees.update', $employee->id), 'method' => 'PUT')) }}
@else
<div class="modal-content" id="addEmployee">
    {{ Form::open(array('url' => 'employees', 'files' => true,)) }}
@endif
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">@if(!empty($employee)) {{__('Edit Employee')}} @else {{__('Add Employee')}}@endif</h4>
</div>
<div class="modal-body" >
    <div class="row">
        <div class="col-md-6">
					
            <div class="form-group row">
                {{ Form::label('name', trans('employee.name').' *', ['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9"> 
                {{ Form::text('name', null, array('class' => 'form-control')) }}
                </div>
            </div>

            <div class="form-group row">
                {{ Form::label('email', trans('employee.email').' *', ['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9"> 
                    {{ Form::text('email', null, array('class' => 'form-control')) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('avatar', trans('employee.choose_avatar'),['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9">
                {{ Form::file('avatar', null, array('class' => 'form-control')) }}
                    @if(isset($employee->avatar))
                    <img src="{{$employee->fileUrl()}}" alt="" height="35">
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                {{ Form::label('password', trans('employee.password'), ['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9"> 
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
            </div>

            <div class="form-group row">
                {{ Form::label('password_confirmation', trans('employee.confirm_password'), ['class'=>'col-sm-3 text-right']) }}
                <div class="col-sm-9"> 
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                </div>
            </div>
        </div>
    </div>
    @if(auth()->user()->checkSpPermission('assaign.roles'))
        <div class="form-group row">
            {{ Form::label('role', __('Role *'), ['class'=>'col-sm-2 text-center']) }}
            <div class="col-sm-10 pl-0"> 
                @foreach($roles as $role)
                <span style="margin-right:30px"><input type="checkbox" name="role[]" value="{{$role->name}}" {{(!empty($employee) && $employee->hasRole($role->name)) ? 'checked' : ''}}> {{ucwords($role->name)}}</span>
                @endforeach
            </div>
        </div>
    @endif
</div>
<div class="modal-footer">
    {{ Form::submit(__('Submit'), array('class' => 'btn btn-success')) }}
    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
</div>
{{ Form::close() }}
</div>