<div class="modal-content" id="showCustomer">
    @php
        $avatar = asset('/dist/img/avatar5.png');
        if (trim($customer->avatar) != 'no-foto.png') {
            $avatar = $customer->fileUrl();
        }
    @endphp
    <section class="content-header">
      <h1><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          {{__('Customer Profile')}}</h1>
    </section>
      
      <!-- Main content -->
    <section class="content">
      @include('partials.flash')
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-success">
              <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{$avatar}}" alt="User profile picture">
                <h3 class="profile-username text-center">{{$customer->name}}</h3>
                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <b>{{__('Balance')}} </b> <a class="pull-right">{{currencySymbol().$customer->prev_balance}}</a>
                  </li>
                  <li class="list-group-item hidden-print">
                    <b>{{__('Total Sales')}} </b> <a class="pull-right">{{$total_sales}}</a>
                  </li>
                </ul>
                <a class="btn btn-success btn-block hidden-print" href="#" data-toggle="modal" data-target="#customerPaymentModal"><b>{{__('Add Payment')}}</b></a>
                <!--Customer Payment Modal start-->
                <div class="modal submodal fade" id="customerPaymentModal" role="dialog">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" onclick="closeEl('.submodal', '#customerPaymentModal')">&times;</button>
                        <h4 class="modal-title">{{__('Add Payment')}}</h4>
                      </div>
                      <div class="modal-body">
                        {{ Form::open(['route'=>'customerpayments.store']) }}
                          <div class="form-group">
                              {{ Form::select('payment_type', $payment_types, null, array('class' => 'form-control','placeholder'=>'Select payment type','required')) }}
                          </div>
                          <div class="form-group"><!---select account input-->
                              {{Form::select('account_id', $accounts, null, ['class'=>'form-control', 'placeholder'=>'Select Account', 'required'])}}
                          </div>
                          <div class="form-group">
                            {{ Form::hidden('customer_id', $customer->id, ['class'=>'form-control']) }}
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
                        <button type="button" class="btn btn-success" onclick="closeEl('.submodal', '#customerPaymentModal')">{{__('Close')}}</button>
                      </div>
                    </div>
                  </div>
                </div><!--Customer Payment Modal End-->

              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
            
           <!-- About Me Box -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">{{__('Last Payment History')}}</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body p-0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>{{__('Date')}}</th>
                      <th>{{__('Payment')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($customer_payments as $customer_payment)
                    <tr>
                      <td>{{$customer_payment->created_at->format('M d')}}</td>
                      <td>{{currencySymbol().$customer_payment->payment}}</td>
                    </tr>
                    @endforeach
                    <tr>
                      <td colspan="3" style="background: #00a65a;padding: 2px;"></td>
                    </tr>
                    @foreach($sale_payments as $sale_payment)
                    <tr>
                      <td>{{$sale_payment->created_at->format('M d')}}</td>
                      <td>{{currencySymbol().$sale_payment->payment}}</td>
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
            <h3 class="mt-0">All Sales</h3>
            @include('customer.partials.sale_table', ['salereport'=>$sales, 'type'=>'customer'])
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
</div>