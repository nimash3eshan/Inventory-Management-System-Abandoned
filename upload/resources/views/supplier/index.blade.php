@extends('layouts.admin_dynamic')

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-3">
      <h1>{{__('Suppliers')}} 
        <a class="btn btn-small btn-default pull-right ml-2" href="{{route('suppliers.export')}}" ><i class="fa fa-download"></i>&nbsp; {{__('export')}}</a>
  <a class="btn btn-small btn-default pull-right ml-2" href="#supplierImportModal" data-toggle="modal"><i class="fa fa-upload"></i>&nbsp; {{__('import')}}</a>
        <a class="btn btn-small btn-success pull-right" href="#addSupplierModal" data-toggle='modal'><i class="fa fa-plus"></i>&nbsp; {{__('Add')}}</a></h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
            <!-- /.box-header -->
            <div class="box box-success">
              <div class="box-header">
                @include('partials.filters', ['filter_route'=>url('/suppliers'), 'filter_id'=>'supplierFilter'])
              </div>
              <div class="box-body">
                  @include('supplier.table')
				      </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="modal fade sub-modal" id="addSupplierModal">
        <div class="modal-dialog modal-lg">
            @include('supplier.form', ['supplier'=>''])
        </div>
      </div>
      <div class="modal fade sub-modal" id="editSupplierModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="editSupplier"></div>
        </div>
      </div>
      <div class="modal fade sub-modal" id="showSupplierModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="showSupplier"></div>
        </div>
      </div>

      @include('partials.import_modal', ['name'=>'Suppliers', 'modalId'=>'supplierImportModal', 'import_route'=>'suppliers.import'])

    </section>
    <!-- /.content -->
  </div>

@endsection
            