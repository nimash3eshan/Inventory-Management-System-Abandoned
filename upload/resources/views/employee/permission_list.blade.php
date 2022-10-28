<div class="row" id="permissionList">
    @foreach($all_permissions as $key=>$svalue)
    <div class="col-sm-3">
        <div class="box box-success permission-list">
            <div class="box-header"><b>{{ucfirst($key)}}</b> <input type="checkbox" id="checkAllPer" class="pull-right" onclick="checkPermissions(this)" {{($role->hasAnyPermission($svalue)) ? 'checked' : ''}}></div>
            <div class="box-body">
                @foreach($svalue as $value)
                    <p><input type="checkbox" name="permissions[]" value="{{$value->id}}" {{($role->hasPermissionTo($value->name)) ? 'checked' : ''}} onclick="checAllkPermissions(this)"> {{$value->label}}</p>
                @endforeach
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    @endforeach
</div>