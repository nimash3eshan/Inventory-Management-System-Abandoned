
      <div class="modal-content" id="expenseCategoryForm">
      {{ Form::open(['route' => 'expensecategory.store', 'method' => 'post']) }}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{__('Add A Expense Category')}}</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            {{ Form::label('name', __('Name')) }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
          </div>
          <div class="form-group">
            {{ Form::label('description', __('Description')) }}
            {{ Form::textarea('description', null, array('class' => 'form-control', 'rows'=>3)) }}
          </div>
          {{Form::hidden('page', $page)}}
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">{{__('Create')}}</button>
        </div>
        {{ Form::close() }}
      </div>
