@extends('layouts.admin_dynamic')

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>{{__('Expenses')}}<a class="btn btn-small btn-success pull-right" href="#addExpenseModal" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Create Expense')}}</a></h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
      
            <div class="box box-success">
              <div class="box-header">
                @include('partials.filters', ['filter_route'=>url('/expense'), 'filter_id'=>'expenseFilter'])
              </div>
            <div class="box-body">
              @include('expense.partials.expense_table', ['expenses'=>$expenses])
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div>
        <div class="modal fade sub-modal" id="addExpenseModal">
          <div class="modal-dialog modal-lg">
              @include('expense.form', ['expense'=>'','page'=>'expense'])
          </div>
        </div>
        <div class="modal fade sub-modal" id="editExpenseModal">
          <div class="modal-dialog modal-lg">
              <div class="modal-content" id="editExpense"></div>
          </div>
        </div>
      </div>
    
    <div class="modal fade" id="expenseCategoryAdd">
      <div class="modal-dialog">
        @include('expense.category_form', ['page'=>'expense'])
      </div>
    </div>

    </section>
    <!-- /.content -->
  </div>
@endsection
