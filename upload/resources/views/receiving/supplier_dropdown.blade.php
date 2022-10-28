<select id='supplier_dropdown' class="form-control select2" name="supplier_id" required>
    <option value="">{{__('Select Supplier')}}</option>
    @foreach($suppliers as $key=>$value)
    <option value="{{$key}}" {{($key== (!empty($selected_supplier) ? $selected_supplier : 0 )) ? "selected" : ""}}>{{$value}}</option>
    @endforeach
</select>
<script src="{{asset('js/partials.js')}}"></script>