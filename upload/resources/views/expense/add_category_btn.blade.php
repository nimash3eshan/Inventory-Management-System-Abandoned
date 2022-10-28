@if($page == 'expense-category')
<div id="category-btns">
    <div class="button-group">
      @foreach($expense_categories as $key=>$value)
      <button type="button" class="btn btn-info">
        <a href="javascript:;" data-ajax-url="{{route('expensecategory.show', $key)}}" class="text-white">{{$value}}</a></button>
      @endforeach
    </div>
</div>
@else
<div class="form-group row" id="addCategoryBtn">
    {{ Form::label('expense_category', __('Category') .' *',['class'=>'col-sm-3 text-right']) }}
    <div class="col-sm-7 no-margin no-right-padding">
    {{ Form::select('expense_category_id', $expense_categories, null, array('class' => 'form-control')) }}
    </div>
    <div class="col-sm-2 no-margin no-left-padding" >
        <a class="btn btn-success pull-right" href="{{url('expenses')}}/#expenseCategoryAdd" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; {{__('Add')}}</a>
    </div>
</div>
@endif