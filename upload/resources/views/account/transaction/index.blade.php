@extends('layouts.admin_dynamic')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header m-3">
            <h1>{{__('Transactions')}} <a class="btn btn-small btn-success pull-right" href="#addTransactionModal" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Create Transaction')}}</a></h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-success">
                        <div class="box-header"></div>
                        <div class="box-body">
                            @include('account.transaction.table')
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="modal fade sub-modal" id="addTransactionModal">
                <div class="modal-dialog modal-lg">
                    @include('account.transaction.form', ['transaction'=>''])
                </div>
            </div>
            <div class="modal fade sub-modal" id="editTransactionModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="editTransaction"></div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
