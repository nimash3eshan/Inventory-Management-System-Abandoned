@extends('layouts.admin_dynamic')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>{{__('Application Settings')}}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-success">
            <div class="">
                <div class="box-header">
                  <div class="box-title">{{__('General Settings')}}</div>
                </div>
                @include('flexiblepos-setting.pos_settings')
      		</div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          @include('flexiblepos-setting.payment_type')
          @include('flexiblepos-setting.mail_settings')
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection