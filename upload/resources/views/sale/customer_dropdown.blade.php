<select id='customer_dropdown' class="form-control select2" name="customer_id" required>
    <option value="">{{__('Select Customer')}}</option>
    @foreach($customers as $customer)
    <option value="{{$customer->id}}" {{($customer->name == (!empty($selected_customer) ? $selected_customer : App\Customer::WALKING_CUSTOMER)) ? "selected" : ""}}>{{$customer->name}}</option>
    @endforeach
</select>
<script src="{{asset('js/partials.js')}}"></script>