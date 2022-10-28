@extends('layouts.admin_dynamic')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>{{__('Employees')}}
        @if($user->checkSpPermission('employees.create'))
        <a class="btn btn-small btn-primary pull-right left-margin-10" href="#addEmployeeModal" data-toggle="modal"><i class="fa fa-plus"></i> {{trans('employee.new_employee')}}</a>
          @endif
          @if($user->checkSpPermission('employeerole.create'))
          <a class="btn btn-small btn-success pull-right left-margin-10" href="#createRole" data-toggle="modal"><i class="fa fa-plus"></i> {{__('Create Role')}}</a>
          @endif
          <a class="btn btn-small btn-info pull-right left-margin-10" href="{{route('permissions.list')}}" data-toggle="modal"><i class="fa fa-plus"></i> {{__('Permissions')}}</a></h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
              @include('partials.filters', ['filter_route'=>url('/employees'), 'filter_id'=>'employeeFilter'])
            </div>
            <div class="box-body">
              @include('employee.table')
			      </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<div class="modal fade sub-modal" id="addEmployeeModal">
  <div class="modal-dialog modal-lg">
      @include('employee.form', ['employee'=>''])
  </div>
</div>
@endsection
