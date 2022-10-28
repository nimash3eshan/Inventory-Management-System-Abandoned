<div id="employeeTable">
    
    <table id="myTable" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>{{trans('employee.name')}}</th>
                <th>{{trans('employee.email')}}</th>
                <th>{{trans('employee.avatar')}}</th>
                <th class="hidden-xs">Role</th>
                <th class="text-center">{{__('Action')}}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($employees as $value)
            <tr>
                <td>{{ $value->name }}</td>
                <td>{{ $value->email }}</td>
                <td class="hidden-xs"><img src="{{$value->fileUrl('avatar')}}" alt="" height="30"></td>
                <td>
                    @php $n=1; @endphp
                    @foreach($value->getRoleNames() as $role) 
                    {{$n > 1 ? ', ':null}}{{ucwords($role)}}
                    @php $n++; @endphp
                     @endforeach</td>
                <td class="item_btn_group">
                    @php
                    $actions = [
                    ['data-replace'=>'#editEmployee','url'=>'#editEmployeeModal','ajax-url'=>url('employees/'.$value->id.'/edit'), 'name'=>trans('item.edit'), 'icon'=>'pencil'],
                    ['url'=>'employees/' . $value->id,'name'=>'delete']];
                    @endphp
                    @include('partials.actions', ['actions'=>$actions])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('partials.pagination', ['items'=>$employees, 'index_route'=>route('employees.index')])

    <div class="modal fade" id="createRole">
        <div class="modal-dialog">
            {{ Form::open(['route' => 'employeerole.create', 'method' => 'post']) }}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{__('Add Employee Role')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', null, array('class' => 'form-control', 'required')) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary" onclick="$('.modal-backdrop').remove();$('body').removeClass('modal-open');">{{__('Create')}}</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>