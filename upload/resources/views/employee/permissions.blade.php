@extends('layouts.admin_dynamic')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header m-3">
            <div class="row">
                <div class="col-xs-6"><h1 class="m-0">{{__('All Permissions')}}</h1></div>
                <div class="col-xs-6 text-right"><button type="button" submit-toggle="#rolePermissionMapping" class="btn btn-success">{{__('Save Permissions')}}</button>@if(auth()->user()->checkSpPermission('permissions.create'))
                    @endif</div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                <!-- /.box-header -->
                    <!-- /.box -->
                    {{ Form::open(['url'=>route('permissionrole.create'), 'id'=>'rolePermissionMapping']) }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="box box-success">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-3 mt-1 text-right"><strong>{{__('Select a Role')}}</strong></div>
                                        <div class="col-xs-8">{{ Form::select('role_id', $roles, $role_id, ['class'=>'form-control', 'onchange'=>'onChange()', 'id'=>'role_id']) }}</div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>
                    @include('employee.permission_list')
                    {{ Form::close() }}
                    <!-- /.box-header -->
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <div class="modal fade" id="createPermission">
        <div class="modal-dialog">
            <!-- {{ Form::open(['route' => 'permissions.create', 'method' => 'post']) }}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{__('Add a Permission')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('label', __('Name')) }}
                        {{ Form::text('label', null, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('name', __('Route')) }}
                        {{ Form::text('name', null, array('class' => 'form-control')) }}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </div>
            </div>
            {{ Form::close() }} -->
        </div>
    </div>
@endsection
