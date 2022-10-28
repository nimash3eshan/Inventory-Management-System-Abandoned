<div class="form-group row" id="addCategoryBtn">
    {{ Form::label('category', __('Category') .' *',['class'=>'col-sm-3 text-right']) }}
    <div class="col-sm-7 no-margin no-right-padding">
    {{ Form::select('category_id', $categories, null, array('class' => 'form-control', "required")) }}
    </div>
    <div class="col-sm-2 no-margin no-left-padding" >
        <a class="btn btn-success pull-right" href="{{url('category')}}/#CategoryAdd" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Add')}}</a>
    </div>
</div>