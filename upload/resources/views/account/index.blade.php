@extends('layouts.admin_dynamic')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header m-3">
            <h1>{{__('Accounts')}} <a class="btn btn-small btn-success pull-right" href="#addAccountModal" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Create Account')}}</a> </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- /.box-header -->
                    <div class="box box-success">
                        <div class="box-header"></div>
                        <div class="box-body">
                            @include('account.table')
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="modal fade sub-modal" id="addAccountModal">
                <div class="modal-dialog modal-lg">
                    @include('account.form', ['account'=>''])
                </div>
            </div>
            <div class="modal fade sub-modal" id="editAccountModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="editAccount"></div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
