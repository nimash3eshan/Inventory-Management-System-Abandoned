<div class="modal fade sub-modal" id="{{$modalId}}" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="addItem">
        {{ Form::open(['route' => $import_route, 'files' => true]) }}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{__('Import').' '.__($name)}} <small>For getting sample data click on <strong>export</strong> button.</small></h4>
        </div>
        <div class="modal-body" >
            <div class="row">
                <div class="form-group ">
                  {{ Form::label('import_file', __('Select file').' *', ['class'=>'col-sm-3 text-right']) }}
                  <div class="col-sm-6"> 
                  {{ Form::file('import_file',null, ['class' => 'form-control', 'required']) }}
                  </div>
                  <div class="col-sm-3">
                    {{ Form::submit(trans('item.submit'), ['class' => 'btn btn-success']) }}
                  </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
      </div>
    </div>
</div>