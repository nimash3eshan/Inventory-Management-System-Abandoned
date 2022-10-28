@extends('layouts.admin_dynamic')

@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header m-3"><h1>{{__('Stocks')}} 
  <a class="btn btn-small btn-default pull-right ml-2" href="{{route('items.export')}}" ><i class="fa fa-download"></i>&nbsp; {{__('export')}}</a>
  <a class="btn btn-small btn-default pull-right ml-2" href="#itemImportModal" data-toggle="modal"><i class="fa fa-upload"></i>&nbsp; {{__('import')}}</a>
  <a class="btn btn-small btn-success pull-right" href="#addItemModal" data-toggle='modal'><i class="fa fa-plus"></i>&nbsp; {{__('Add')}}</a></h1></section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
        <!-- /.box-header -->
        <div class="box box-success">
          <div class="box-header">
            @include('partials.filters', ['filter_route'=>url('/items'), 'filter_id'=>'itemFilter'])
          </div>
            <div class="box-body">
              @include('item.table')
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="modal fade sub-modal" id="addItemModal">
        <div class="modal-dialog modal-lg">
            @include('item.form', ['item'=>''])
        </div>
      </div>
      <div class="modal fade sub-modal" id="editItemModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="editItem"></div>
        </div>
      </div>
      <div class="modal fade sub-modal" id="inventoryModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="inventory"></div>
        </div>
      </div>
      <div class="modal fade sub-modal" id="itemAttributeModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="item_attribute"></div>
        </div>
      </div>
      @include('partials.import_modal', ['name'=>'Items', 'modalId'=>'itemImportModal', 'import_route'=>'items.import'])

      <div class="modal fade" id="CategoryAdd">
        <div class="modal-dialog">
          @include('item.category_form', ['page'=>'expense'])
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
@endsection

