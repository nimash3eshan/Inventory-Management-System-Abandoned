<div class="" id="customerDetails">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3"><h1>{{__('Customers')}}
      <a class="btn btn-small btn-default pull-right ml-2" href="{{route('customers.export')}}" ><i class="fa fa-download"></i>&nbsp; {{__('export')}}</a>
  <a class="btn btn-small btn-default pull-right ml-2" href="#customerImportModal" data-toggle="modal"><i class="fa fa-upload"></i>&nbsp; {{__('import')}}</a>
      <a class="btn btn-small btn-success pull-right" href="#addCustomerModal" data-toggle='modal'>
      <i class="fa fa-plus"></i>&nbsp; {{__('Add')}}</a></h1></section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
              <div class="box-header">
                @include('partials.filters', ['filter_route'=>url('/customers'), 'filter_id'=>'customerFilter'])
              </div>
            <div class="box-body">
              @include('customer.table')
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="modal fade sub-modal" id="addCustomerModal">
        <div class="modal-dialog modal-lg">
            @include('customer.form', ['customer'=>''])
        </div>
      </div>
      <div class="modal fade sub-modal" id="editCustomerModal">
        <div class="modal-dialog modal-lg">
            <div id="editCustomer"></div>
        </div>
      </div>
      <div class="modal fade sub-modal" id="showCustomerModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="showCustomer"></div>
        </div>
      </div>
      @include('partials.import_modal', ['name'=>'Customers', 'modalId'=>'customerImportModal', 'import_route'=>'customers.import'])

    </section>
    <!-- /.content -->
  </div>