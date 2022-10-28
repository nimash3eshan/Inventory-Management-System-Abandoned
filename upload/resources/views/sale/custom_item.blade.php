<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{__('Custom item Details')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                            <div class="form-group" id="category_id">
                                <select class="form-control" ng-model="customitem.category_id" name="category_id" id="category_id">
                                    <option value="" selected>Select Category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>                    
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                        <input type="text" ng-model="customitem.item_name" name="item_name" class="form-control" id="item_name" placeholder="{{__('Item Name')}}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                            <div class="form-group">
                                <input type="number" ng-model="customitem.quantity" name="quantity" class="form-control" id="quantity" placeholder="{{__('Qty')}}" required>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                            <div class="form-group">
                                <input type="number" ng-model="customitem.selling_price" name="selling_price" class="form-control" id="selling_price" placeholder="{{__('Selling Price')}}" required>
                            </div>
                    </div>
                  <div class="col-12 col-md-4">
                        <div class="form-group">
                                <input type="number" ng-model="customitem.cost_price" name="cost_price" class="form-control" id="cost_price" placeholder="{{__('Cost Price')}}" required>
                        </div>
                  </div>
                </div>
                    <div class="text-right">
                        <button ng-click="addCustomItem(customitem)" class="btn btn-success custom-item-btn">{{trans('item.submit')}}</button>
                    </div>
            </div>
        </div>
    </div>
</div>