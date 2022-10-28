@extends('layouts.admin_dynamic')

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>{{__('Category')}}<a class="btn btn-small btn-success pull-right" href="#expenseCategoryAdd" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Create Category')}}</a></h1>
      
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
            <!-- /.box-header -->
          <!-- /.box -->
            <!-- /.box-header -->
            <div class="box box-success">
              <div class="box-header">
                @include('item.add_category_btn', ['page'=>'expense-category'])
              </div>
            <div class="box-body">
              <div id="expenseTable">
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="modal fade" id="CategoryAdd">
        <div class="modal-dialog">
          @include('item.category_form', ['page'=>'expense-category'])
        </div>
      </div>
      <div class="modal fade sub-modal" id="editExpenseModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="editExpense"></div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
@endsection
