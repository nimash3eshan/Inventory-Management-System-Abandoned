<div class="modal-content" id="showSupplier">

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>{{__('Supplier Profile')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    @include('partials.flash')
  <div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-success">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="{{$supplier->fileUrl()}}" alt="User profile picture">

          <h3 class="profile-username text-center">{{$supplier->name}}</h3>
          <p class="text-muted text-center"> {{__('supplier Info')}}</p>
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Balance </b> <a class="pull-right">{{$supplier->prev_balance}}</a>
            </li>
            <li class="list-group-item">
              <b>Total Receivings </b> <a class="pull-right">{{number_format($total_receivings,2)}}</a>
            </li>
          </ul>
          <a class="btn btn-success btn-block" href="#" data-toggle="modal" data-target="#supplierPaymentModal"><b>{{__('Add Payment')}}</b></a>
          <div class="modal submodal fade" id="supplierPaymentModal" role="dialog">
            <div class="modal-dialog modal-sm">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" onclick="closeEl('.submodal', '#supplierPaymentModal')">&times;</button>
                  <h4 class="modal-title">{{__('Add Payment')}}</h4>
                </div>
                <div class="modal-body">
                  {{ Form::open(['route'=>'supplierpayments.store']) }}
                  <div class="form-group">
                    {{ Form::select('payment_type', $payment_types, null, array('class' => 'form-control','placeholder'=>'Select a payment type','required')) }}
                  </div>
                  <div class="form-group"><!---select account input-->
                    {{Form::select('account_id', $accounts, null, ['class'=>'form-control', 'placeholder'=>'Select Account', 'required'])}}
                </div>
                  <div class="form-group">
                    {{ Form::hidden('supplier_id', $supplier->id, ['class'=>'form-control','required']) }}
                    {{ Form::number('payment', null, ['class'=>'form-control', 'placeholder'=>'Amount', 'required']) }}
                  </div>
                  <div class="form-group">
                    {{ Form::text('comments', null, ['class'=>'form-control','placeholder'=>'Comments']) }}
                  </div>
                  <div class="form-group">
                    {{ Form::submit('Add Payment', ['class'=>'btn btn-success', 'onclick'=>"$('.modal-backdrop').remove()"]) }}
                  </div>
                  {{ Form::close() }}
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" onclick="closeEl('.submodal', '#supplierPaymentModal')">{{__('Close')}}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      
     <!-- About Me Box -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{__('Payment History')}}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body p-0">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>{{__('Date')}}</th>
                <th>{{__('Payment')}}</th>
                {{-- <th class="hidden-md">{{__('Received')}}</th> --}}
              </tr>
            </thead>
            <tbody>
              @foreach($supplier_payments as $supplier_payment)
              <tr>
                <td>{{$supplier_payment->created_at->format('M d')}}</td>
                <td>{{$supplier_payment->payment}}</td>
                {{-- <td class="hidden-md">{{$supplier_payment->user->name}}</td> --}}
              </tr>
              @endforeach
              <tr>
                <td colspan="3" class="border-x"></td>
              </tr>
              @foreach($receiving_payments as $receiving_payment)
              <tr>
                <td>{{$receiving_payment->created_at->format('M d')}}</td>
                <td>{{$receiving_payment->payment}}</td>
                {{-- <td class="hidden-md">{{$receiving_payment->user->name}}</td> --}}
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <h3 class="mt-0">{{__('All Purchases')}}</h3>
        @include('supplier.partials.receiving_table', ['receivingreport'=>$receivings, 'type'=>'supplier'])
      
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

</section>
<!-- /.content -->
</div>
