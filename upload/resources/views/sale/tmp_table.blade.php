<table class="table table-hover table-striped">
    <tr>
        <th class="hidden-xs">{{trans('sale.item_id')}}</th>
        <th>{{trans('sale.item_name')}}</th>
        <th>{{trans('sale.price')}}</th>
        <th>{{trans('sale.quantity')}}</th>
        <th>{{trans('sale.total')}}</th>
        <th>&nbsp;</th>
    </tr>
    <tr ng-repeat="newsaletemp in saletemp">
        <td class="hidden-xs">@{{newsaletemp.item_id}}</td>
        <td>@{{newsaletemp.item_name}}</td>
        <td>{{currencySymbol()}}
            <input type="text" style="text-align:center" autocomplete="off" name="selling_price" ng-change="updateSaleTemp(newsaletemp)" ng-model="newsaletemp.selling_price" size="4">
        </td>
        <td><input type="text" style="text-align:center" autocomplete="off" name="quantity" ng-change="updateSaleTemp(newsaletemp)" ng-model="newsaletemp.quantity" size="2"></td>
        <td>{{currencySymbol()}}@{{newsaletemp.selling_price * newsaletemp.quantity }}</td>
        <td>
            <button class="btn btn-danger btn-xs" type="button" ng-click="removeSaleTemp(newsaletemp.id)">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="text-center"><a href="#" data-toggle="modal" data-target=".bs-example-modal-lg">{{__('Add Quick Product')}}</a></td>
    </tr>
</table>