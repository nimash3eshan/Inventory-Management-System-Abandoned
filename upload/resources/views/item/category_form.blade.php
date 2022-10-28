
      <div class="modal-content" id="CategoryForm">
        {{ Form::open(['route' => 'category.store', 'method' => 'post']) }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{__('Add A Category')}}</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              {{ Form::label('name', __('Name')) }}
              {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
            <input type="hidden" name="type" value="item">
            <input type="hidden" name="description" value="description">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">{{__('Create')}}</button>
          </div>
          {{ Form::close() }}
        </div>


        
  